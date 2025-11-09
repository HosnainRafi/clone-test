<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Publication;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PublicationController extends BaseController
{
    public function index(Request $request)
    {
        $siteData = $this->getSiteData($request);
        $siteId = $siteData['id'] ?? 1;

        // Load publications from DB for the admin editor
        $publicationItems = Publication::forSite($siteId)
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'abstract' => $item->abstract,
                    // authors column stored as comma-separated string in DB
                    'authors' => $item->authors ? array_map('trim', explode(',', $item->authors)) : [],
                    'correspondingAuthor' => $item->publisher ?? '',
                    'journal' => $item->journal,
                    'journalRank' => $item->journal_rank,
                    'impactFactor' => $item->impact_factor,
                    'publishDate' => $item->published_at?->format('Y-m-d') ?? null,
                    'volume' => $item->volume,
                    'issue' => $item->issue,
                    'pages' => $item->pages,
                    'doi' => $item->doi,
                    'category' => $item->publication_type,
                    'keywords' => $item->keywords ?? [],
                    'citations' => $item->citation_count,
                    'downloads' => $item->download_count,
                    'openAccess' => $item->is_open_access,
                    'featured' => $item->is_featured,
                    'fallbackGradient' => $item->fallback_gradient,
                    'pdfUrl' => ($item->attachments['pdfUrl'] ?? null) ?? null,
                    'journalUrl' => ($item->attachments['journalUrl'] ?? null) ?? ($item->url ?? null),
                ];
            })
            ->values()
            ->all();

        return Inertia::render('TopPublication/Index', [
            'publicationItems' => $publicationItems,
            'siteId' => $siteId,
        ]);
    }

    public function save(Request $request)
    {
        $request->validate([
            'publicationItems' => 'required|array',
            'siteId' => 'required|integer',
        ]);

        $siteId = $request->input('siteId');
        $items = $request->input('publicationItems', []);

        $incomingIds = [];

        foreach ($items as $raw) {
            // Normalize incoming item
            $item = (array) $raw;

            $data = [];
            $data['title'] = $item['title'] ?? 'Untitled';
            $data['abstract'] = $item['abstract'] ?? null;
            // authors stored as comma-separated string in DB
            if (isset($item['authors']) && is_array($item['authors'])) {
                $data['authors'] = implode(', ', array_map('trim', $item['authors']));
            } else {
                $data['authors'] = is_string($item['authors']) ? $item['authors'] : null;
            }
            $data['journal'] = $item['journal'] ?? null;
            $data['journal_rank'] = $item['journalRank'] ?? null;
            $data['impact_factor'] = $item['impactFactor'] ?? 0;
            $data['conference'] = $item['conference'] ?? null;
            $data['publication_type'] = $item['category'] ?? ($item['type'] ?? 'article');
            $data['doi'] = $item['doi'] ?? null;
            $data['url'] = $item['journalUrl'] ?? ($item['url'] ?? null);
            $data['publisher'] = $item['correspondingAuthor'] ?? null;
            $data['citation_count'] = $item['citations'] ?? 0;
            $data['download_count'] = $item['downloads'] ?? 0;
            $data['is_open_access'] = $item['openAccess'] ?? false;
            $data['is_featured'] = $item['featured'] ?? false;
            $data['fallback_gradient'] = $item['fallbackGradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
            // year - try to derive from publishDate if provided
            if (!empty($item['publishDate'])) {
                $data['published_at'] = $item['publishDate'];
                $data['year'] = date('Y', strtotime($item['publishDate']));
            } else {
                $data['year'] = $item['year'] ?? now()->format('Y');
            }
            $data['volume'] = $item['volume'] ?? null;
            $data['issue'] = $item['issue'] ?? null;
            $data['pages'] = $item['pages'] ?? null;
            $data['citation_count'] = $item['citations'] ?? null;
            $data['download_count'] = $item['downloads'] ?? null;
            $data['is_open_access'] = $item['openAccess'] ?? false;
            $data['is_featured'] = $item['featured'] ?? false;
            $data['fallback_gradient'] = $item['fallbackGradient'] ?? null;
            $data['keywords'] = $item['keywords'] ?? [];

            // attachments - include pdfUrl and journalUrl and any meta
            $attachments = [];
            if (!empty($item['pdfUrl'])) $attachments['pdfUrl'] = $item['pdfUrl'];
            if (!empty($item['journalUrl'])) $attachments['journalUrl'] = $item['journalUrl'];
            // add meta details for future use
            $attachments['meta'] = [
                'impactFactor' => $item['impactFactor'] ?? null,
                'citations' => $item['citations'] ?? null,
                'downloads' => $item['downloads'] ?? null,
                'openAccess' => $item['openAccess'] ?? false,
                'featured' => $item['featured'] ?? false,
                'fallbackGradient' => $item['fallbackGradient'] ?? null,
            ];
            $data['attachments'] = $attachments;

            $data['link'] = $item['link'] ?? null;
            $data['image'] = $item['image'] ?? null;
            $data['is_active'] = true;
            $data['site_id'] = $siteId;
            $data['updated_by'] = Auth::id();

            // Create or update
            if (!empty($item['id']) && is_numeric($item['id'])) {
                $pub = Publication::where('id', $item['id'])->where('site_id', $siteId)->first();
                if ($pub) {
                    $pub->update($data);
                } else {
                    $pub = Publication::create($data + ['created_by' => Auth::id()]);
                }
            } else {
                $pub = Publication::create($data + ['created_by' => Auth::id()]);
            }

            $incomingIds[] = $pub->id;
        }

        // Delete any publications for this site that were removed in the admin UI
        Publication::forSite($siteId)->whereNotIn('id', $incomingIds)->delete();

        // Return fresh items for admin to rehydrate
        $publicationItems = Publication::forSite($siteId)
            ->orderBy('published_at', 'desc')
            ->orderBy('sort_order', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'abstract' => $item->abstract,
                    'authors' => $item->authors ? array_map('trim', explode(',', $item->authors)) : [],
                    'correspondingAuthor' => $item->publisher ?? '',
                    'journal' => $item->journal,
                    'journalRank' => $item->journal_rank,
                    'impactFactor' => $item->impact_factor,
                    'publishDate' => $item->published_at?->format('Y-m-d') ?? null,
                    'volume' => $item->volume,
                    'issue' => $item->issue,
                    'pages' => $item->pages,
                    'doi' => $item->doi,
                    'category' => $item->publication_type,
                    'keywords' => $item->keywords ?? [],
                    'citations' => $item->citation_count,
                    'downloads' => $item->download_count,
                    'openAccess' => $item->is_open_access,
                    'featured' => $item->is_featured,
                    'fallbackGradient' => $item->fallback_gradient,
                    'pdfUrl' => ($item->attachments['pdfUrl'] ?? null) ?? null,
                    'journalUrl' => ($item->attachments['journalUrl'] ?? null) ?? ($item->url ?? null),
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'success' => true,
            'message' => 'Publications saved successfully',
            'publicationItems' => $publicationItems,
        ]);
    }
}
