<script setup lang="ts">
import TiptapEditor from '@/components/TiptapEditor.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';
import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface TeacherItem {
    id: number;
    site_id?: number | null;
    slug?: string;
    name: string;
    designation?: string;
    department?: string;
    profile_image?: string;
    email?: string;
    phone_number?: string;
    office_location?: string;
    website_url?: string;
    about_me?: string;
    research_interests: string[];
    education: Array<{ degree: string; field: string; university: string; year: string }>;
    experience: Array<{ role: string; institution: string; period: string }>;
    publications: Array<{ title: string; authors?: string; journal?: string; conference?: string; year?: number; link?: string; category?: string }>;
    projects: Array<{ title: string; role?: string; funding_source?: string; status?: string }>;
    courses_taught: Array<{ code?: string; title: string }>;
    awards: Array<{ title: string; event?: string; year?: string }>;
    social_links: { linkedin?: string; google_scholar?: string; researchgate?: string; [k: string]: any };
    is_active: boolean;
    sort_order: number;
}

interface PageProps {
    teachers: TeacherItem[];
    siteId: number | null;
    siteType?: string;
    departments: Array<{ id: number; name: string; subdomain: string }>;
}

const props = defineProps<PageProps>();

const teacherData = ref<TeacherItem[]>([]);
const viewMode = ref<'list' | 'form'>('list');
const editingItem = ref<TeacherItem | null>(null);
const isSaving = ref(false);
const message = ref('');
const messageType = ref<'success' | 'error' | ''>('');

const deepClone = <T,>(v: T): T => JSON.parse(JSON.stringify(v));
const interestInput = ref<HTMLInputElement | null>(null);
const uploading = ref(false);
const uploadError = ref('');
const initialize = () => {
    teacherData.value = deepClone(props.teachers || []);
};
initialize();

const hasUnsavedChanges = computed(() => JSON.stringify(teacherData.value) !== JSON.stringify(props.teachers));

const showMessage = (msg: string, type: 'success' | 'error') => {
    message.value = msg;
    messageType.value = type;
    setTimeout(() => (message.value = ''), 5000);
};

const slugify = (text: string) =>
    text
        .toString()
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');

const defaultTeacher = (): TeacherItem => ({
    id: Date.now(),
    site_id: props.siteId || (props.departments && props.departments.length > 0 ? props.departments[0].id : null),
    slug: '',
    name: '',
    designation: '',
    department: props.departments && props.departments.length > 0 ? props.departments[0].name : '',
    profile_image: '',
    email: '',
    phone_number: '',
    office_location: '',
    website_url: '',
    about_me: '',
    research_interests: [],
    education: [],
    experience: [],
    publications: [],
    projects: [],
    courses_taught: [],
    awards: [],
    social_links: { linkedin: '', google_scholar: '', researchgate: '' },
    is_active: true,
    sort_order: (teacherData.value?.length || 0) + 1,
});

const onAdd = () => {
    editingItem.value = defaultTeacher();
    viewMode.value = 'form';
};

const onEdit = (t: TeacherItem) => {
    editingItem.value = deepClone(t);
    viewMode.value = 'form';
};

const onDelete = (id: number) => {
    const idx = teacherData.value.findIndex((x) => x.id === id);
    if (idx > -1) {
        teacherData.value.splice(idx, 1);
        teacherData.value.forEach((x, i) => (x.sort_order = i + 1));
        showMessage('Teacher removed.', 'success');
        validateAndSave();
    }
};

const onCancel = () => {
    editingItem.value = null;
    viewMode.value = 'list';
};

const ensureSlug = () => {
    if (!editingItem.value) return;
    if (!editingItem.value.slug && editingItem.value.name) {
        editingItem.value.slug = slugify(editingItem.value.name);
    }
};

const onSaveForm = () => {
    if (!editingItem.value) return;
    ensureSlug();
    const idx = teacherData.value.findIndex((x) => x.id === editingItem.value!.id);
    if (idx > -1) teacherData.value[idx] = editingItem.value;
    else teacherData.value.push(editingItem.value);
    viewMode.value = 'list';
    editingItem.value = null;
    showMessage('Teacher saved locally.', 'success');
    validateAndSave();
};

const resetToOriginal = () => {
    initialize();
    showMessage('Changes discarded.', 'success');
};

const validateAndSave = async () => {
    if (!props.siteId) return showMessage('Site ID missing.', 'error');
    isSaving.value = true;
    try {
        // Determine the correct route based on site type
        const siteType = props.siteType || 'university';
        let saveRoute = '/admin/teachers'; // Legacy fallback

        if (siteType === 'university') {
            saveRoute = '/admin/university/teachers';
        } else if (siteType === 'department') {
            saveRoute = '/admin/department/teachers';
        }

        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const response = await fetch(saveRoute, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, Accept: 'application/json' },
            body: JSON.stringify({ teachers: teacherData.value }),
        });
        const result = await response.json().catch(() => ({}));
        if (response.ok && result.success) {
            showMessage(result.message || 'Saved.', 'success');
            router.reload({ only: ['teachers'], onSuccess: () => initialize() });
        } else {
            showMessage(result.message || 'Server error', 'error');
        }
    } catch (e) {
        console.error(e);
        showMessage('Network error', 'error');
    } finally {
        isSaving.value = false;
    }
};

// helpers for arrays
const pushEducation = () => editingItem.value?.education.push({ degree: '', field: '', university: '', year: '' });
const removeEducation = (i: number) => editingItem.value?.education.splice(i, 1);
const pushExperience = () => editingItem.value?.experience.push({ role: '', institution: '', period: '' });
const removeExperience = (i: number) => editingItem.value?.experience.splice(i, 1);
const pushPublication = () => editingItem.value?.publications.push({ title: '', authors: '', journal: '', year: new Date().getFullYear() });
const removePublication = (i: number) => editingItem.value?.publications.splice(i, 1);
const pushProject = () => editingItem.value?.projects.push({ title: '', role: '', funding_source: '', status: '' });
const removeProject = (i: number) => editingItem.value?.projects.splice(i, 1);
const pushCourse = () => editingItem.value?.courses_taught.push({ code: '', title: '' });
const removeCourse = (i: number) => editingItem.value?.courses_taught.splice(i, 1);
const pushAward = () => editingItem.value?.awards.push({ title: '', event: '', year: '' });
const removeAward = (i: number) => editingItem.value?.awards.splice(i, 1);
const addInterest = (val: string) => {
    if (!editingItem.value) return;
    const v = (val || '').trim();
    if (!v) return;
    if (!editingItem.value.research_interests.includes(v)) editingItem.value.research_interests.push(v);
};
const removeInterest = (i: number) => editingItem.value?.research_interests.splice(i, 1);

const onEnterInterest = () => {
    const v = interestInput.value?.value || '';
    addInterest(v);
    if (interestInput.value) interestInput.value.value = '';
};
const onClickAddInterest = () => {
    const v = interestInput.value?.value || '';
    addInterest(v);
    if (interestInput.value) interestInput.value.value = '';
};

const updateDepartmentName = () => {
    if (!editingItem.value) return;
    const selectedDept = props.departments.find((d) => d.id === editingItem.value!.site_id);
    if (selectedDept) {
        editingItem.value.department = selectedDept.name;
    }
};

const onProfileImageSelected = async (e: Event) => {
    uploadError.value = '';
    if (!props.siteId) {
        uploadError.value = 'Site ID missing';
        return;
    }
    const input = e.target as HTMLInputElement;
    const file = input?.files?.[0];
    if (!file) return;
    uploading.value = true;
    try {
        // Determine the correct route based on site type
        const siteType = props.siteType || 'university';
        let uploadRoute = '/admin/teachers/upload-image'; // Legacy fallback

        if (siteType === 'university') {
            uploadRoute = '/admin/university/teachers/upload-image';
        } else if (siteType === 'department') {
            uploadRoute = '/admin/department/teachers/upload-image';
        }

        const form = new FormData();
        form.append('file', file);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const res = await fetch(uploadRoute, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf },
            body: form,
        });
        const json = await res.json().catch(() => ({}));
        if (!res.ok || !json.success) {
            throw new Error(json.message || 'Upload failed');
        }
        if (editingItem.value) editingItem.value.profile_image = json.url;
        showMessage('Image uploaded', 'success');
    } catch (err: any) {
        console.error(err);
        uploadError.value = err?.message || 'Upload error';
    } finally {
        uploading.value = false;
    }
};
</script>

<template>
    <DefaultLayout>
        <div
            class="shadow-default rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 sm:px-7.5 xl:pb-1 dark:border-strokedark dark:bg-boxdark"
        >
            <!-- Header -->
            <div class="mb-6">
                <div class="mb-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-xl font-semibold text-black dark:text-white">Teachers Management</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Site ID: {{ siteId || 'N/A' }}
                            <span v-if="hasUnsavedChanges" class="ml-2 text-warning">• Unsaved Changes</span>
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button v-if="viewMode === 'list'" @click="onAdd" class="action-btn bg-primary">Add Teacher</button>
                        <button v-if="hasUnsavedChanges" @click="resetToOriginal" class="action-btn bg-secondary">Discard All</button>
                    </div>
                </div>
                <div
                    v-if="message"
                    :class="messageType === 'success' ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger'"
                    class="mb-4 rounded-md border px-4 py-3"
                >
                    {{ message }}
                </div>
            </div>

            <!-- List View -->
            <div v-if="viewMode === 'list'">
                <div class="max-w-full overflow-x-auto">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Name</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Designation</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Department</th>
                                <!-- <th class="px-4 py-4 font-medium text-black dark:text-white">Active</th>
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Order</th> -->
                                <th class="px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in teacherData" :key="item.id">
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.name }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.designation }}</p>
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <p class="text-black dark:text-white">{{ item.department || 'N/A' }}</p>
                                    <p v-if="item.site_id" class="text-xs text-gray-500">Site ID: {{ item.site_id }}</p>
                                </td>
                                <!-- <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <span
                                        :class="item.is_active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger'"
                                        class="rounded-full px-3 py-1 text-xs font-medium"
                                        >{{ item.is_active ? 'Yes' : 'No' }}</span
                                    >
                                </td>
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    {{ item.sort_order }}
                                </td> -->
                                <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                    <div class="flex items-center space-x-3.5">
                                        <button @click="onEdit(item)" class="action-btn bg-primary px-3 py-1 text-sm">Edit</button>
                                        <button @click="onDelete(item.id)" class="action-btn bg-danger px-3 py-1 text-sm">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="teacherData.length === 0">
                                <td colspan="6" class="py-10 text-center text-gray-500">No teachers found. Click "Add Teacher" to begin.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Form View -->
            <div v-if="viewMode === 'form' && editingItem" class="space-y-6">
                <div class="rounded-sm border border-stroke bg-gray-50 p-4 dark:bg-meta-4">
                    <h5 class="mb-4 text-lg font-medium text-black dark:text-white">
                        {{ editingItem.id > 1000000000000 ? 'Add Teacher' : 'Edit Teacher' }}
                    </h5>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="form-label">Name *</label>
                            <input v-model="editingItem.name" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Slug</label>
                            <div class="flex gap-2">
                                <input v-model="editingItem.slug" type="text" class="form-input" />
                                <button type="button" class="action-btn bg-secondary" @click="editingItem.slug = slugify(editingItem.name || '')">
                                    Generate
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Designation</label>
                            <input v-model="editingItem.designation" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Department *</label>
                            <select v-model.number="editingItem.site_id" class="form-input" @change="updateDepartmentName">
                                <option value="" disabled>Select Department</option>
                                <option v-for="dept in departments" :key="dept.id" :value="dept.id">
                                    {{ dept.name }}
                                </option>
                            </select>
                            <p v-if="editingItem.site_id" class="mt-1 text-xs text-gray-500">Site ID: {{ editingItem.site_id }}</p>
                        </div>
                        <div>
                            <label class="form-label">Profile Image URL</label>
                            <div class="space-y-2">
                                <input v-model="editingItem.profile_image" type="text" class="form-input" placeholder="https://... or upload below" />
                                <div class="flex items-center gap-3">
                                    <input type="file" accept="image/*" @change="onProfileImageSelected" :disabled="uploading" />
                                    <span v-if="uploading" class="text-sm text-gray-500">Uploading...</span>
                                    <span v-if="uploadError" class="text-sm text-danger">{{ uploadError }}</span>
                                </div>
                                <div v-if="editingItem.profile_image" class="mt-2">
                                    <img :src="editingItem.profile_image" alt="Preview" class="h-20 w-20 rounded border object-cover" />
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Email</label>
                            <input v-model="editingItem.email" type="email" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Phone Number</label>
                            <input v-model="editingItem.phone_number" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Office Location</label>
                            <input v-model="editingItem.office_location" type="text" class="form-input" />
                        </div>
                        <div>
                            <label class="form-label">Website URL</label>
                            <input v-model="editingItem.website_url" type="text" class="form-input" />
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">About</label>
                            <TiptapEditor v-model="editingItem.about_me" />
                        </div>
                    </div>
                </div>

                <!-- Research Interests -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Research Interests</h6>
                        <div class="flex gap-2">
                            <input
                                ref="interestInput"
                                type="text"
                                placeholder="Add interest"
                                class="w-64 rounded border-[1.5px] border-stroke bg-transparent px-4 py-2 text-sm outline-none focus:border-primary dark:border-form-strokedark dark:bg-form-input"
                                @keyup.enter="onEnterInterest"
                            />
                            <button type="button" class="action-btn bg-primary px-3 py-1.5 text-sm" @click="onClickAddInterest">Add</button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="(r, i) in editingItem.research_interests"
                            :key="i"
                            class="inline-flex items-center gap-2 rounded-full border border-success/20 bg-success/10 px-4 py-1.5 text-sm font-medium text-success dark:border-success/30 dark:bg-success/20"
                        >
                            {{ r }}
                            <button type="button" class="font-bold text-success transition-colors hover:text-danger" @click="removeInterest(i)">
                                ×
                            </button>
                        </span>
                        <span v-if="!editingItem.research_interests.length" class="text-sm text-gray-400">No research interests added yet.</span>
                    </div>
                </div>

                <!-- Education -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Education</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushEducation">+ Add Education</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(ed, i) in editingItem.education" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Degree</label>
                                    <input v-model="ed.degree" class="form-input" placeholder="e.g., PhD, MSc, BSc" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Field</label>
                                    <input v-model="ed.field" class="form-input" placeholder="e.g., Computer Science" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">University</label>
                                    <input v-model="ed.university" class="form-input" placeholder="e.g., Oxford University" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Year</label>
                                    <input v-model="ed.year" class="form-input" placeholder="e.g., 2020" />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removeEducation(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.education.length" class="text-center text-sm text-gray-400">No education records added yet.</p>
                    </div>
                </div>

                <!-- Experience -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Experience</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushExperience">+ Add Experience</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(ex, i) in editingItem.experience" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-3">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Role</label>
                                    <input v-model="ex.role" class="form-input" placeholder="e.g., Assistant Professor" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Institution</label>
                                    <input v-model="ex.institution" class="form-input" placeholder="e.g., MIT" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Period</label>
                                    <input v-model="ex.period" class="form-input" placeholder="e.g., 2018-2022" />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removeExperience(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.experience.length" class="text-center text-sm text-gray-400">No experience records added yet.</p>
                    </div>
                </div>

                <!-- Publications -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Publications</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushPublication">+ Add Publication</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(p, i) in editingItem.publications" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Title</label>
                                    <input v-model="p.title" class="form-input" placeholder="Publication title" />
                                </div>
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white">Authors</label>
                                        <input v-model="p.authors" class="form-input" placeholder="Author names" />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white">Category</label>
                                        <select v-model="p.category" class="form-input">
                                            <option value="">Select category</option>
                                            <option value="Journal Article">Journal Article</option>
                                            <option value="Conference Paper">Conference Paper</option>
                                            <option value="Book Chapter">Book Chapter</option>
                                            <option value="Book">Book</option>
                                            <option value="Technical Report">Technical Report</option>
                                            <option value="Thesis">Thesis</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white">Journal/Conference</label>
                                        <input v-model="p.journal" class="form-input" placeholder="Journal or Conference name" />
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-black dark:text-white">Year</label>
                                        <input v-model.number="p.year" type="number" class="form-input" placeholder="2024" />
                                    </div>
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Link</label>
                                    <input v-model="p.link" class="form-input" placeholder="https://..." />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removePublication(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.publications.length" class="text-center text-sm text-gray-400">No publications added yet.</p>
                    </div>
                </div>

                <!-- Projects -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Projects</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushProject">+ Add Project</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(pr, i) in editingItem.projects" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Title</label>
                                    <input v-model="pr.title" class="form-input" placeholder="Project title" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Role</label>
                                    <input v-model="pr.role" class="form-input" placeholder="e.g., Principal Investigator" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Funding Source</label>
                                    <input v-model="pr.funding_source" class="form-input" placeholder="e.g., NSF Grant" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Status</label>
                                    <input v-model="pr.status" class="form-input" placeholder="e.g., Ongoing, Completed" />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removeProject(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.projects.length" class="text-center text-sm text-gray-400">No projects added yet.</p>
                    </div>
                </div>

                <!-- Courses -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Courses Taught</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushCourse">+ Add Course</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(c, i) in editingItem.courses_taught" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Code</label>
                                    <input v-model="c.code" class="form-input" placeholder="e.g., CS101" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Title</label>
                                    <input v-model="c.title" class="form-input" placeholder="e.g., Introduction to Programming" />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removeCourse(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.courses_taught.length" class="text-center text-sm text-gray-400">No courses added yet.</p>
                    </div>
                </div>

                <!-- Awards -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h6 class="text-base font-semibold text-black dark:text-white">Awards & Honors</h6>
                        <button class="action-btn bg-primary px-3 py-1.5 text-sm" @click="pushAward">+ Add Award</button>
                    </div>
                    <div class="space-y-4">
                        <div v-for="(a, i) in editingItem.awards" :key="i" class="rounded-md bg-white p-4 dark:bg-boxdark-2">
                            <div class="mb-3 grid grid-cols-1 gap-3 md:grid-cols-3">
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Title</label>
                                    <input v-model="a.title" class="form-input" placeholder="Award title" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Event</label>
                                    <input v-model="a.event" class="form-input" placeholder="Event or Organization" />
                                </div>
                                <div>
                                    <label class="mb-1 block text-sm font-medium text-black dark:text-white">Year</label>
                                    <input v-model="a.year" class="form-input" placeholder="e.g., 2024" />
                                </div>
                            </div>
                            <div class="flex justify-end">
                                <button class="action-btn bg-danger px-3 py-1 text-sm" @click="removeAward(i)">Remove</button>
                            </div>
                        </div>
                        <p v-if="!editingItem.awards.length" class="text-center text-sm text-gray-400">No awards added yet.</p>
                    </div>
                </div>

                <!-- Status and Order -->
                <!-- <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <h6 class="mb-4 text-base font-semibold text-black dark:text-white">Settings</h6>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="form-label">Active Status</label>
                            <select v-model="(editingItem as any).is_active" class="form-input">
                                <option :value="true">Active</option>
                                <option :value="false">Inactive</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Display Order</label>
                            <input v-model.number="(editingItem as any).sort_order" type="number" min="0" class="form-input" placeholder="1" />
                        </div>
                    </div>
                </div> -->

                <!-- Social Links -->
                <div class="rounded-sm border border-stroke bg-gray-50 p-6 dark:bg-meta-4">
                    <h6 class="mb-4 text-base font-semibold text-black dark:text-white">Social & Academic Links</h6>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-black dark:text-white">LinkedIn</label>
                            <input v-model="editingItem.social_links.linkedin" class="form-input" placeholder="https://linkedin.com/in/..." />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-black dark:text-white">Google Scholar</label>
                            <input
                                v-model="editingItem.social_links.google_scholar"
                                class="form-input"
                                placeholder="https://scholar.google.com/..."
                            />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-black dark:text-white">ResearchGate</label>
                            <input v-model="editingItem.social_links.researchgate" class="form-input" placeholder="https://researchgate.net/..." />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button @click="onCancel" class="action-btn bg-gray-500">Cancel</button>
                    <button @click="onSaveForm" class="action-btn bg-success">Save</button>
                </div>
            </div>

            <!-- Global Save Button -->
            <div class="mt-6 flex justify-end" v-if="hasUnsavedChanges">
                <button
                    @click="validateAndSave"
                    :disabled="isSaving || !siteId || !hasUnsavedChanges"
                    class="save-btn"
                    :class="{
                        'hover:bg-opacity-90 bg-primary': hasUnsavedChanges,
                        'cursor-not-allowed bg-gray-400': isSaving || !siteId || !hasUnsavedChanges,
                    }"
                >
                    {{ isSaving ? 'Saving...' : 'Save All Changes' }}
                </button>
            </div>
        </div>
    </DefaultLayout>
</template>

<style scoped>
@reference '../../../../resources/css/app.css';
.action-btn {
    @apply inline-flex items-center justify-center rounded-md px-4 py-2 text-center text-sm font-medium text-white;
}
.form-label {
    @apply mb-2.5 block font-medium text-black dark:text-white;
}
.form-input {
    @apply w-full rounded border-[1.5px] border-stroke bg-transparent px-5 py-3 font-medium outline-none focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input;
}
.save-btn {
    @apply inline-flex items-center justify-center rounded-md px-6 py-3 text-center font-medium text-white transition-colors;
}
</style>
