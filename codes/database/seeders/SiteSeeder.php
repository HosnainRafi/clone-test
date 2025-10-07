<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Default menu items structure
        $defaultMenuItems = [
            [
                'title' => 'About',
                'col' => 2,
                'subItems' => [
                    [
                        'title' => 'About Mawlana Bhashani',
                        'description' => 'Learn about the visionary leader and founder\'s philosophy',
                        'href' => 'https://mbstu.ac.bd/about-mawlana-bhashani/'
                    ],
                    [
                        'title' => 'Overview',
                        'description' => 'Comprehensive overview of the university',
                        'href' => 'https://mbstu.ac.bd/overview/'
                    ],
                    [
                        'title' => 'Vision & Mission',
                        'description' => 'Our commitment to excellence in science and technology education',
                        'href' => 'https://mbstu.ac.bd/vision-mission/'
                    ],
                    [
                        'title' => 'Achievements',
                        'description' => 'Awards, recognitions and milestones of our university',
                        'href' => 'https://mbstu.ac.bd/achievements/'
                    ],
                    [
                        'title' => 'University Facts & Acts',
                        'description' => 'Legal framework and founding documents',
                        'href' => 'https://mbstu.ac.bd/university-facts-acts/'
                    ],
                    [
                        'title' => 'Contact Us',
                        'description' => 'Get in touch with MBSTU',
                        'href' => 'https://mbstu.ac.bd/contact/'
                    ]
                ]
            ],
            [
                'title' => 'Academics',
                'col' => 3,
                'subItems' => [
                    [
                        'title' => 'Academic Calendar',
                        'description' => 'Academic calendar, schedules and important dates',
                        'href' => 'https://mbstu.ac.bd/academic-calender/'
                    ],
                    [
                        'title' => 'Undergraduate Programs',
                        'description' => 'Bachelor\'s degree programs across all faculties',
                        'href' => 'https://mbstu.ac.bd/academics-detail/'
                    ],
                    [
                        'title' => 'Faculty of Engineering',
                        'description' => 'Engineering disciplines and programs',
                        'href' => 'https://mbstu.ac.bd/faculty-of-engineering/'
                    ],
                    [
                        'title' => 'Faculty of Life Science',
                        'description' => 'Life sciences and biological studies',
                        'href' => 'https://mbstu.ac.bd/faculty-of-life-science/'
                    ],
                    [
                        'title' => 'Faculty of Science',
                        'description' => 'Pure and applied sciences',
                        'href' => 'https://mbstu.ac.bd/faculty-of-science/'
                    ],
                    [
                        'title' => 'Faculty of Business Studies',
                        'description' => 'Business administration and management',
                        'href' => 'https://mbstu.ac.bd/faculty-of-business-studies/'
                    ]
                ]
            ],
            [
                'title' => 'Faculties',
                'col' => 2,
                'subItems' => [
                    [
                        'title' => 'Faculty of Engineering',
                        'description' => 'Engineering disciplines and programs',
                        'href' => 'https://mbstu.ac.bd/faculty-of-engineering/'
                    ],
                    [
                        'title' => 'Faculty of Life Science',
                        'description' => 'Life sciences and biological studies',
                        'href' => 'https://mbstu.ac.bd/faculty-of-life-science/'
                    ],
                    [
                        'title' => 'Faculty of Science',
                        'description' => 'Pure and applied sciences',
                        'href' => 'https://mbstu.ac.bd/faculty-of-science/'
                    ],
                    [
                        'title' => 'Faculty of Business Studies',
                        'description' => 'Business administration and management',
                        'href' => 'https://mbstu.ac.bd/faculty-of-business-studies/'
                    ],
                    [
                        'title' => 'Faculty of Social Science',
                        'description' => 'Social sciences and humanities',
                        'href' => 'https://mbstu.ac.bd/faculty-of-social-science/'
                    ],
                    [
                        'title' => 'Faculty of Arts',
                        'description' => 'Arts, literature and cultural studies',
                        'href' => 'https://mbstu.ac.bd/faculty-of-arts/'
                    ],
                    [
                        'title' => 'Faculty of Veterinary Medicine',
                        'description' => 'Veterinary medicine and animal sciences',
                        'href' => 'https://mbstu.ac.bd/faculty-of-veterinary-medicine-and-animal-sciences/'
                    ]
                ]
            ],
            [
                'title' => 'Administration and Offices',
                'col' => 4,
                'subItems' => [
                    [
                        'title' => 'Vice Chancellor',
                        'description' => 'Office of the Vice Chancellor',
                        'href' => 'https://mbstu.ac.bd/vice-chancellor/'
                    ],
                    [
                        'title' => 'Pro Vice Chancellor',
                        'description' => 'Office of the Pro Vice Chancellor',
                        'href' => 'https://mbstu.ac.bd/pro-vice-chancellor/'
                    ],
                    [
                        'title' => 'Treasurer',
                        'description' => 'Office of the Treasurer',
                        'href' => 'https://mbstu.ac.bd/treasurer/'
                    ],
                    [
                        'title' => 'The Regent Board',
                        'description' => 'University governing body',
                        'href' => 'https://mbstu.ac.bd/the-regent-board/'
                    ],
                    [
                        'title' => 'Registrar Office',
                        'description' => 'Academic records and administration',
                        'href' => 'https://mbstu.ac.bd/registrar-office/'
                    ],
                    [
                        'title' => 'Academic Council',
                        'description' => 'Academic governance and policies',
                        'href' => 'https://mbstu.ac.bd/academic-council/'
                    ],
                    [
                        'title' => 'Dean of Faculties',
                        'description' => 'Faculty leadership and administration',
                        'href' => 'https://mbstu.ac.bd/deans/'
                    ],
                    [
                        'title' => 'Chairman of The Departments',
                        'description' => 'Departmental heads and leadership',
                        'href' => 'https://mbstu.ac.bd/chairman-of-the-departments/'
                    ],
                    [
                        'title' => 'Research Centre',
                        'description' => 'Research coordination and development',
                        'href' => 'https://mbstu.ac.bd/research-centre/'
                    ],
                    [
                        'title' => 'Research Cell',
                        'description' => 'Research planning and support',
                        'href' => 'https://mbstu.ac.bd/research-cell/'
                    ],
                    [
                        'title' => 'IQAC',
                        'description' => 'Internal Quality Assurance Cell',
                        'href' => 'https://mbstu.ac.bd/iqac/'
                    ],
                    [
                        'title' => 'Central Library',
                        'description' => 'Library resources and services',
                        'href' => 'https://mbstu.ac.bd/central-library/'
                    ],
                    [
                        'title' => 'Medical Center',
                        'description' => 'Healthcare services for students and staff',
                        'href' => 'https://mbstu.ac.bd/medical-center/'
                    ]
                ]
            ],
            [
                'title' => 'Research',
                'col' => 1,
                'subItems' => [
                    [
                        'title' => 'Research Facilities',
                        'description' => 'State-of-the-art research infrastructure',
                        'href' => 'https://mbstu.ac.bd/research-facilities/'
                    ],
                    [
                        'title' => 'Research Centers',
                        'description' => 'Specialized research centers and institutes',
                        'href' => 'https://mbstu.ac.bd/research-centers/'
                    ],
                    [
                        'title' => 'Research Labs',
                        'description' => 'Laboratory facilities and equipment',
                        'href' => 'https://mbstu.ac.bd/research-labs/'
                    ],
                    [
                        'title' => 'Research Cell',
                        'description' => 'Research coordination and support',
                        'href' => 'https://mbstu.ac.bd/research-cell/'
                    ],
                    [
                        'title' => 'Journal',
                        'description' => 'MBSTU Journal and publications',
                        'href' => 'https://journal.mbstu.ac.bd/'
                    ]
                ]
            ],
            [
                'title' => 'Campus Life',
                'col' => 2,
                'subItems' => [
                    [
                        'title' => 'Student Life',
                        'description' => 'Campus activities, clubs and student organizations',
                        'href' => 'https://mbstu.ac.bd/campus-life/'
                    ],
                    [
                        'title' => 'Residential Halls',
                        'description' => 'On-campus housing and accommodation',
                        'href' => 'https://mbstu.ac.bd/residential-halls/'
                    ],
                    [
                        'title' => 'Central Library',
                        'description' => 'Library resources and digital collections',
                        'href' => 'https://mbstu.ac.bd/central-library/'
                    ],
                    [
                        'title' => 'Medical Center',
                        'description' => 'Healthcare services for students and staff',
                        'href' => 'https://mbstu.ac.bd/medical-center/'
                    ],
                    [
                        'title' => 'Clubs & Organizations',
                        'description' => 'Student clubs and extracurricular activities',
                        'href' => 'https://mbstu.ac.bd/bhashani/clubs/'
                    ],
                    [
                        'title' => 'Student Welfare',
                        'description' => 'Student support and counseling services',
                        'href' => 'https://mbstu.ac.bd/students-welfare-and-counseling/'
                    ]
                ]
            ],
            [
                'title' => 'Admission',
                'col' => 1,
                'subItems' => [
                    [
                        'title' => 'Undergraduate',
                        'description' => 'Bachelor\'s degree admission information and requirements',
                        'href' => 'https://mbstu.ac.bd/undergraduate-admission/'
                    ],
                    [
                        'title' => 'Graduate',
                        'description' => 'Master\'s and PhD admission information and requirements',
                        'href' => 'https://mbstu.ac.bd/graduate-admission/'
                    ],
                    [
                        'title' => 'Others',
                        'description' => 'Other admission programs and requirements',
                        'href' => 'https://mbstu.ac.bd/other-admission/'
                    ],
                    [
                        'title' => 'International Students',
                        'description' => 'Admission information for international students',
                        'href' => 'https://mbstu.ac.bd/international-students/'
                    ]
                ]
            ],
            [
                'title' => 'Notices',
                'col' => 1,
                'subItems' => [
                    [
                        'title' => 'Notices',
                        'description' => 'Official notices and announcements',
                        'href' => 'https://mbstu.ac.bd/category/notices/'
                    ],
                    [
                        'title' => 'News',
                        'description' => 'Latest news and updates from MBSTU',
                        'href' => 'https://mbstu.ac.bd/category/news/'
                    ],
                    [
                        'title' => 'Events',
                        'description' => 'Upcoming events and activities',
                        'href' => 'https://mbstu.ac.bd/category/events/'
                    ],
                    [
                        'title' => 'Tender',
                        'description' => 'Tender notices and procurement information',
                        'href' => 'https://mbstu.ac.bd/category/tender/'
                    ]
                ]
            ]
        ];

        // Create sample sites
        $sites = [
            [
                'name' => 'MBSTU Main Website',
                'description' => 'Main university website for Mawlana Bhashani Science and Technology University',
                'domain' => 'localhost',
                'subdomain' => null,
                'theme_id' => 1,
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'siteTitle' => 'Mawlana Bhashani Science and Technology University',
                    'siteDescription' => 'Leading science and technology university in Bangladesh',
                    'contactEmail' => 'info@mbstu.ac.bd',
                    'contactPhone' => '+880-2-9291234',
                    'address' => 'Santosh, Tangail-1902, Bangladesh',
                    'socialMedia' => [
                        'facebook' => 'https://facebook.com/mbstu',
                        'twitter' => 'https://twitter.com/mbstu',
                        'linkedin' => 'https://linkedin.com/company/mbstu'
                    ]
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'ICT Department',
                'description' => 'Department of Information and Communication Technology',
                'domain' => 'ict.localhost',
                'subdomain' => 'ict',
                'theme_id' => 1,
                'is_active' => true,
                'settings' => [
                    'menuItems' => array_slice($defaultMenuItems, 0, 5), // Subset of menu items
                    'siteTitle' => 'Department of ICT - MBSTU',
                    'siteDescription' => 'Information and Communication Technology Department',
                    'contactEmail' => 'ict@mbstu.ac.bd',
                    'departmentHead' => 'Dr. John Doe',
                    'establishedYear' => 2010
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'CSE Department',
                'description' => 'Department of Computer Science and Engineering',
                'domain' => 'cse.localhost',
                'subdomain' => 'cse',
                'theme_id' => 1,
                'is_active' => true,
                'settings' => [
                    'menuItems' => array_slice($defaultMenuItems, 0, 6), // Different subset
                    'siteTitle' => 'Department of CSE - MBSTU',
                    'siteDescription' => 'Computer Science and Engineering Department',
                    'contactEmail' => 'cse@mbstu.ac.bd',
                    'departmentHead' => 'Dr. Jane Smith',
                    'establishedYear' => 2008,
                    'programs' => ['BSc in CSE', 'MSc in CSE', 'PhD in CSE']
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Engineering Faculty',
                'description' => 'Faculty of Engineering - MBSTU',
                'domain' => 'engineering.localhost',
                'subdomain' => 'engineering',
                'theme_id' => 1,
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'siteTitle' => 'Faculty of Engineering - MBSTU',
                    'siteDescription' => 'Engineering education and research excellence',
                    'contactEmail' => 'engineering@mbstu.ac.bd',
                    'dean' => 'Prof. Dr. Ahmed Rahman',
                    'departments' => [
                        'Computer Science and Engineering',
                        'Electrical and Electronic Engineering',
                        'Civil Engineering',
                        'Mechanical Engineering'
                    ]
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'name' => 'Development Site',
                'description' => 'Development and testing site',
                'domain' => '127.0.0.1',
                'subdomain' => null,
                'theme_id' => 1,
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'siteTitle' => 'MBSTU - Development',
                    'siteDescription' => 'Development environment for MBSTU website',
                    'contactEmail' => 'dev@mbstu.ac.bd',
                    'environment' => 'development'
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ]
        ];

        // Insert sites
        foreach ($sites as $siteData) {
            Site::create($siteData);
        }

        $this->command->info('Sites seeded successfully!');
        $this->command->info('Created ' . count($sites) . ' sites with menu configurations.');
    }
}
