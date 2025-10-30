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


        $defaultTopBarLinks = [
            [
                'title' => 'Forms & Services',
                'href' => 'https://mbstu.ac.bd/form-download/'
            ],
            [
                'title' => 'Int\'l Students',
                'href' => 'https://mbstu.ac.bd/international-students/'
            ],
            [
                'title' => 'NOC',
                'href' => 'https://mbstu.ac.bd/noc/'
            ],
            [
                'title' => 'IP Phone',
                'href' => 'https://mbstu.ac.bd/noc/'
            ],
            [
                'title' => 'UCAM',
                'href' => 'https://mbstu.ac.bd/noc/'
            ],
            [
                'title' => 'Contact',
                'href' => 'http://ucam.mbstu.ac.bd/Security/LogIn.aspx'
            ],
            [
                'title' => 'Departments',
                'href' => 'http://ucam.mbstu.ac.bd/Security/LogIn.aspx'
            ]
        ];

        // Default login link
        $defaultLoginLink = [
            'title' => 'LOGIN',
            'href' => 'https://mbstu.ac.bd/account/'
        ];


        $defaultHeroSlides = [
    [
        'title' => 'Welcome to MBSTU',
        'subtitle' => 'Mawlana Bhashani Science and Technology University',
        'description' => 'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
        'image' => '/images/carousel/mbstu-campus.jpg',
        'fallbackGradient' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
        'ctaText' => 'About MBSTU',
        'ctaLink' => '/about',
        'secondaryCta' => [
            'text' => 'Admission',
            'link' => '/admission'
        ]
    ],
    [
        'title' => 'Academic Excellence',
        'subtitle' => '7 Faculties & 33 Departments',
        'description' => 'Offering undergraduate and graduate programs across diverse fields including Engineering, Science, Agriculture, and Social Sciences.',
        'image' => '/images/carousel/academic-building.jpg',
        'fallbackGradient' => 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
        'ctaText' => 'Explore Programs',
        'ctaLink' => '/academic',
        'secondaryCta' => null
    ],
    [
        'title' => 'Research & Innovation',
        'subtitle' => 'Advancing Knowledge',
        'description' => 'Pioneering research in emerging technologies, sustainable development, and scientific breakthroughs that shape the future.',
        'image' => '/images/carousel/research-lab.jpg',
        'fallbackGradient' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
        'ctaText' => 'View Research',
        'ctaLink' => '/research',
        'secondaryCta' => null
    ]
];


$defaultHeadlines = [
    [
        'type' => 'announcement',
        'text' => '🎓 Admission Open for Fall 2025 - Apply now for undergraduate and graduate programs',
        'link' => '/admission',
        'priority' => 'high',
        'isActive' => true,
        'icon' => '🎓'
    ],
    [
        'type' => 'news',
        'text' => '🏆 CSE Department wins National Programming Contest 2025',
        'link' => '/news/programming-contest-2025',
        'priority' => 'medium',
        'isActive' => true,
        'icon' => '🏆'
    ],
    [
        'type' => 'event',
        'text' => '📅 International Conference on AI & Machine Learning - March 15-17, 2025',
        'link' => '/events/ai-conference-2025',
        'priority' => 'high',
        'isActive' => true,
        'icon' => '📅'
    ],
    [
        'type' => 'research',
        'text' => '🔬 New Research Lab inaugurated for Cybersecurity and Blockchain Technology',
        'link' => '/research/cybersecurity-lab',
        'priority' => 'medium',
        'isActive' => true,
        'icon' => '🔬'
    ],
    [
        'type' => 'achievement',
        'text' => '🌟 Dr. Rahman receives Best Faculty Award for outstanding research contribution',
        'link' => '/faculty/dr-rahman-award',
        'priority' => 'medium',
        'isActive' => true,
        'icon' => '🌟'
    ],
    [
        'type' => 'notice',
        'text' => '📚 New Digital Library resources available - Access 10,000+ technical journals',
        'link' => '/library/digital-resources',
        'priority' => 'low',
        'isActive' => true,
        'icon' => '📚'
    ]
];


$defaultMessageFromItems = [
            [
                'name' => 'Professor Dr. Md. Anwarul Azim Akhand',
                'title' => 'Vice Chancellor',
                'message' => 'Welcome to Mawlana Bhashani Science and Technology University (MBSTU), a leading center for academic excellence and research in Bangladesh. MBSTU is named after the great visionary and advocate for the underprivileged, Mawlana Abdul Hamid Khan Bhashani. His legacy of dedication to justice, education, and empowerment continues to inspire our mission to create future leaders through scientific innovation, academic rigor, and social commitment.

At MBSTU, we believe that education is not just a means to personal advancement, but a tool for transforming society. Our dynamic curriculum, state-of-the-art facilities, and dedicated faculty aim to nurture creativity, critical thinking, and problem-solving skills among our students. We strive to ensure that our graduates are not only equipped with technical knowledge but also imbued with a sense of responsibility towards the community and the nation.

Our focus on research and innovation encourages students to explore new horizons in science and technology, driving progress in key areas such as computer science, information technology, biotechnology, criminology, engineering, environmental sciences, and many more. Through our diverse programs, we aim to foster a spirit of inquiry leading to solutions for the challenges of tomorrow.',
                'image' => '/images/faculty/vc.jpg',
                'fallbackGradient' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
                'designation' => 'Vice Chancellor',
                'department' => 'Mawlana Bhashani Science and Technology University',
                'email' => 'vc@mbstu.ac.bd',
                'phone' => '+880921 55399',
                'fax' => '+880921 55400',
                'office' => 'Vice Chancellor Office, MBSTU',
                'address' => 'Santosh, Tangail - 1902',
                'officeTime' => 'Saturday - Wednesday: 9.00AM - 5.00PM',
                'experience' => '25+ years',
                'qualifications' => [
                    'Ph.D. in Computer Science and Engineering',
                    'M.Sc. in Computer Science and Engineering',
                    'B.Sc. in Computer Science and Engineering'
                ],
                'specializations' => [
                    'Artificial Intelligence',
                    'Machine Learning',
                    'Computer Vision',
                    'Pattern Recognition',
                    'Evolutionary Computing'
                ],
                'achievements' => [
                    'Leading MBSTU as the 12th oldest public university in Bangladesh',
                    'Pioneer in science and technology education',
                    'Advocate for research and innovation',
                    'Champion of inclusive and quality education'
                ],
                'isActive' => true,
                'displayOrder' => 1,
                'type' => 'vice_chancellor'
            ]
        ];






        //Faculties
        $defaultFacultyItems = [
            [
                'name' => 'Faculty of Engineering',
                'shortName' => 'Engineering',
                'description' => 'Leading innovation in technology and engineering with cutting-edge research, industry partnerships, and world-class laboratories preparing students for the digital future.',
                'image' => '/images/faculties/engineering.jpg',
                'fallbackGradient' => 'linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%)',
                'link' => '/faculty-of-engineering',
                'departments' => [
                    'Computer Science and Engineering (CSE)',
                    'Information and Communication Technology (ICT)',
                    'Electrical and Electronic Engineering (EEE)',
                    'Civil Engineering (CE)'
                ],
                'stats' => [
                    'departments' => 4,
                    'students' => '1,250+',
                    'faculty' => '48+',
                    'programs' => 12
                ],
                'highlights' => [
                    'State-of-the-art laboratories',
                    'Industry collaboration',
                    'Research excellence',
                    'International partnerships'
                ],
                'researchAreas' => [
                    'Artificial Intelligence & Machine Learning',
                    'Robotics & Automation',
                    'Renewable Energy Systems',
                    'Smart Infrastructure'
                ],
                'achievements' => [
                    '15+ International Research Publications (2024)',
                    'IEEE Student Branch Chapter',
                    'Industry Collaboration with 20+ Companies',
                    '90% Graduate Employment Rate'
                ],
                'featured' => true,
                'established' => '2002',
                'ranking' => 'Top Rated',
                'dean' => [
                    'name' => 'Prof. Dr. Mohammad Motiur Rahman',
                    'title' => 'Dean, Faculty of Engineering',
                    'email' => 'dean.engineering@mbstu.ac.bd'
                ],
                'iconName' => 'Calculator',
                'isActive' => true,
                'displayOrder' => 1
            ],
            [
                'name' => 'Faculty of Life Science',
                'shortName' => 'Life Science',
                'description' => 'Exploring life sciences through advanced research in biotechnology, environmental science, and molecular biology with modern laboratories and experienced faculty.',
                'image' => '/images/faculties/life-science.jpg',
                'fallbackGradient' => 'linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%)',
                'link' => '/faculty-of-life-science',
                'departments' => [
                    'Biotechnology and Genetic Engineering (BGE)',
                    'Microbiology (MB)',
                    'Biochemistry and Molecular Biology (BMB)',
                    'Environmental Science (ES)'
                ],
                'stats' => [
                    'departments' => 4,
                    'students' => '850+',
                    'faculty' => '38+',
                    'programs' => 10
                ],
                'highlights' => [
                    'Advanced biotechnology labs',
                    'Environmental research center',
                    'Genetic engineering facility',
                    'Molecular biology research'
                ],
                'researchAreas' => [
                    'Genetic Engineering & Biotechnology',
                    'Environmental Monitoring',
                    'Microbial Technology',
                    'Biochemical Analysis'
                ],
                'achievements' => [
                    'Bio-safety Level 2 Laboratory',
                    '12+ Research Projects Funded',
                    'Environmental Impact Studies',
                    'International Collaboration Programs'
                ],
                'featured' => true,
                'established' => '2003',
                'ranking' => 'Excellence',
                'dean' => [
                    'name' => 'Prof. Dr. Fatima Khatun',
                    'title' => 'Dean, Faculty of Life Science',
                    'email' => 'dean.lifescience@mbstu.ac.bd'
                ],
                'iconName' => 'Microscope',
                'isActive' => true,
                'displayOrder' => 2
            ],
            [
                'name' => 'Faculty of Science',
                'shortName' => 'Science',
                'description' => 'Advancing fundamental sciences through research in mathematics, physics, chemistry, and statistics with emphasis on computational and analytical approaches.',
                'image' => '/images/faculties/science.jpg',
                'fallbackGradient' => 'linear-gradient(135deg, #7c3aed 0%, #8b5cf6 50%, #a78bfa 100%)',
                'link' => '/faculty-of-science',
                'departments' => [
                    'Mathematics (Math)',
                    'Physics (Phy)',
                    'Chemistry (Chem)',
                    'Statistics (Stat)'
                ],
                'stats' => [
                    'departments' => 4,
                    'students' => '650+',
                    'faculty' => '32+',
                    'programs' => 8
                ],
                'highlights' => [
                    'Research laboratories',
                    'Mathematical modeling center',
                    'Computational physics lab',
                    'Analytical chemistry facility'
                ],
                'researchAreas' => [
                    'Pure & Applied Mathematics',
                    'Computational Physics',
                    'Analytical Chemistry',
                    'Statistical Modeling'
                ],
                'achievements' => [
                    'National Mathematics Olympiad Host',
                    '8+ Journal Publications (2024)',
                    'Research Collaboration with BUET',
                    'Scientific Calculator Development'
                ],
                'established' => '2002',
                'ranking' => 'Premier',
                'dean' => [
                    'name' => 'Prof. Dr. Ahmed Hassan',
                    'title' => 'Dean, Faculty of Science',
                    'email' => 'dean.science@mbstu.ac.bd'
                ],
                'iconName' => 'Globe',
                'isActive' => true,
                'displayOrder' => 3
            ],
            [
                'name' => 'Faculty of Business Studies',
                'shortName' => 'Business',
                'description' => 'Developing future business leaders through comprehensive management education, entrepreneurship programs, and strong industry connections.',
                'image' => '/images/faculties/business.jpg',
                'fallbackGradient' => 'linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%)',
                'link' => '/faculty-of-business-studies',
                'departments' => [
                    'Business Administration (BBA)',
                    'Accounting and Information Systems (AIS)',
                    'Marketing (MKT)',
                    'Finance and Banking (F&B)'
                ],
                'stats' => [
                    'departments' => 4,
                    'students' => '950+',
                    'faculty' => '28+',
                    'programs' => 9
                ],
                'highlights' => [
                    'Industry partnerships',
                    'Entrepreneurship incubator',
                    'Case study methodology',
                    'Professional certification'
                ],
                'researchAreas' => [
                    'Digital Marketing',
                    'Financial Technology',
                    'Sustainable Business',
                    'Entrepreneurship Development'
                ],
                'achievements' => [
                    'Business Incubation Center',
                    '25+ Industry Partnerships',
                    'Annual Business Fair',
                    '85% Job Placement Rate'
                ],
                'established' => '2005',
                'ranking' => 'Growing',
                'dean' => [
                    'name' => 'Prof. Dr. Nasreen Ahmed',
                    'title' => 'Dean, Faculty of Business Studies',
                    'email' => 'dean.business@mbstu.ac.bd'
                ],
                'iconName' => 'Briefcase',
                'isActive' => true,
                'displayOrder' => 4
            ]
        ];




        $defaultWelcomeItems = [
    [
        'title' => 'Welcome to MBSTU',
        'description' => 'A leading public university in Bangladesh dedicated to advancing science, technology, and innovation for national development.',
        'backgroundImage' => 'https://mbstu.ac.bd/wp-content/uploads/2023/08/home-Video.jpg',
        'videoId' => 'PZ9MHpFet34',
        'buttonText' => 'Watch Campus Video',
        'isActive' => true,
        'displayOrder' => 1
    ]
];

$defaultGlanceItems = [
            ['id' => 1, 'label' => 'Active Students', 'value' => '5000+', 'iconName' => 'Users', 'iconColor' => '#3b82f6', 'isActive' => true, 'displayOrder' => 1],
            ['id' => 2, 'label' => 'Student Clubs', 'value' => '25+', 'iconName' => 'Trophy', 'iconColor' => '#10b981', 'isActive' => true, 'displayOrder' => 2],
            ['id' => 3, 'label' => 'Residential Halls', 'value' => '6+', 'iconName' => 'Home', 'iconColor' => '#8b5cf6', 'isActive' => true, 'displayOrder' => 3],
            ['id' => 4, 'label' => 'Annual Events', 'value' => '100+', 'iconName' => 'Calendar', 'iconColor' => '#f59e0b', 'isActive' => true, 'displayOrder' => 4],
        ];

$defaultCampusLifeItems = [
          [
            'id' => 1,
            'title' => 'Student Life',
            'category' => 'Campus Experience',
            'description' => 'At MBSTU, student success is our priority. Our campus ensures that all students have the support and resources they need to thrive in this dynamic learning environment.',
            'image' => '/images/campus-life/student-life.jpg',
            'fallbackGradient' => 'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 50%, #1e40af 100%)',
            'iconName' => 'Users',
            'features' => ['Vibrant student community', 'Academic support services', 'Student organizations', 'Leadership opportunities'],
            'stats' => [['label' => 'Active Students', 'value' => '5000+'], ['label' => 'Student Clubs', 'value' => '25+']],
            'link' => '/campus-life',
            'featured' => true,
            'isActive' => true,
            'displayOrder' => 1
          ],
          [
            'id' => 2,
            'title' => 'Arts & Culture',
            'category' => 'Cultural Activities',
            'description' => 'Experience rich cultural diversity through various artistic and cultural programs that celebrate creativity, tradition, and modern expression.',
            'image' => '/images/campus-life/arts-culture.jpg',
            'fallbackGradient' => 'linear-gradient(135deg, #f59e0b 0%, #d97706 50%, #92400e 100%)',
            'iconName' => 'Palette',
            'features' => ['Cultural festivals', 'Art exhibitions', 'Music performances', 'Traditional celebrations'],
            'stats' => [['label' => 'Cultural Events', 'value' => '50+/year'], ['label' => 'Art Societies', 'value' => '8+']],
            'link' => '/arts-culture',
            'featured' => true,
            'isActive' => true,
            'displayOrder' => 2
          ],

        ];



        $defaultNoticeItems = [
            [
                'id' => 1, 'title' => 'Fall 2025 Semester Admission Deadline Extended', 'content' => 'The deadline has been extended to November 15, 2025. Eligible candidates can apply through the university portal.', 'date' => '2025-10-18', 'category' => 'Admission', 'priority' => 'high', 'department' => 'Admission Office', 'isActive' => true, 'displayOrder' => 1
            ],
            [
                'id' => 2, 'title' => 'Final Examination Schedule Published', 'content' => 'The final examination for the current semester will commence from December 1, 2025.', 'date' => '2025-10-16', 'category' => 'Examination', 'priority' => 'high', 'department' => 'Controller of Examinations', 'isActive' => true, 'displayOrder' => 2
            ],
        ];



$defaultNewsItems = [
            [
                'id' => 1,
                'title' => 'CSE Department Achieves Top National Ranking',
                'excerpt' => 'The Department of Computer Science and Engineering has been recognized as one of the top 5 programs in the country for its outstanding research output and academic excellence.',
                'image' => '/images/news/cse-ranking.jpg',
                'date' => '2025-10-15',
                'category' => 'Achievement',
                'link' => '/news/cse-top-ranking-2025',
                'isActive' => true,
                'displayOrder' => 1,
            ],
            [
                'id' => 2,
                'title' => 'New AI Research Lab Inaugurated by Vice Chancellor',
                'excerpt' => 'A state-of-the-art laboratory for Artificial Intelligence and Machine Learning has been inaugurated to foster innovation and advanced research projects.',
                'image' => '/images/news/ai-lab.jpg',
                'date' => '2025-10-12',
                'category' => 'Research',
                'link' => '/news/ai-lab-inauguration',
                'isActive' => true,
                'displayOrder' => 2,
            ],
            [
                'id' => 3,
                'title' => 'Annual Tech Fest "Innovate 2025" Announced',
                'excerpt' => 'The university is excited to announce "Innovate 2025", our annual technology festival, scheduled for November. It will feature coding competitions, project showcases, and seminars.',
                'image' => '/images/news/tech-fest.jpg',
                'date' => '2025-10-10',
                'category' => 'Event',
                'link' => '/events/innovate-2025',
                'isActive' => true,
                'displayOrder' => 3,
            ],
        ];

        $defaultEventItems = [
            [
                'id' => 1,
                'title' => 'International Conference on Artificial Intelligence and Machine Learning',
                'description' => 'Join leading researchers and industry experts for presentations on cutting-edge AI technologies.',
                'date' => '2025-03-15',
                'time' => '09:00 AM',
                'endDate' => '2025-03-17',
                'venue' => 'MBSTU Auditorium',
                'category' => 'Conference',
                'status' => 'upcoming',
                'registration' => 'Open',
                'fee' => 'Free for students',
                'organizer' => 'CSE Department',
                'participants' => '200+ expected',
                'isActive' => true,
                'displayOrder' => 1,
                'link' => '/events/international-conference-on-ai',
            ],
            [
                'id' => 2,
                'title' => 'Annual Tech Fest 2025 - CodeCraft',
                'description' => 'Three-day technology festival featuring programming contests, workshops, and tech exhibitions.',
                'date' => '2025-02-20',
                'time' => '10:00 AM',
                'endDate' => '2025-02-22',
                'venue' => 'University Campus',
                'category' => 'Festival',
                'status' => 'upcoming',
                'registration' => 'Open',
                'fee' => 'Registration required',
                'organizer' => 'Student Council',
                'participants' => '500+ participants',
                'isActive' => true,
                'displayOrder' => 2,
                'link' => '/events/annual-tech-fest-2025',
            ],
        ];
$defaultNoticeItems = [
            [
                'id' => 1,
                'title' => 'Fall 2025 Semester Admission Application Deadline Extended',
                'content' => '<p>The deadline for Fall 2025 semester admission applications has been extended to February 15, 2025. Eligible candidates can apply through the university portal.</p>',
                'date' => '2025-01-18',
                'category' => 'Admission',
                'priority' => 'high',
                'department' => 'Admission Office',
                'validUntil' => '2025-02-15',
                'attachments' => ['admission-guidelines.pdf', 'application-form.pdf'],
                'isActive' => true,
                'displayOrder' => 1,
                'link' => '/notices/admission-deadline-extended-fall-2025'
            ],
            [
                'id' => 2,
                'title' => 'Spring 2025 Final Examination Schedule',
                'content' => '<p>The final examination for Spring 2025 semester will commence from March 1, 2025. Detailed schedule and hall ticket information available on student portal.</p>',
                'date' => '2025-01-16',
                'category' => 'Examination',
                'priority' => 'high',
                'department' => 'Controller of Examinations',
                'validUntil' => '2025-03-01',
                'attachments' => ['exam-schedule.pdf', 'guidelines.pdf'],
                'isActive' => true,
                'displayOrder' => 2,
                'link' => '/notices/spring-2025-exam-schedule'
            ],
        ];


        $defaultPublicationItems = [
            [
                'id' => 1,
                'title' => 'Deep Learning Approaches for Predictive Analytics in Educational Data Mining: A Comprehensive Study',
                'abstract' => 'This paper presents a comprehensive analysis of deep learning methodologies applied to educational data mining, focusing on student performance prediction and learning path optimization.',
                'authors' => ['Dr. Rahman Ahmed', 'Prof. Fatima Khan', 'Dr. Nasir Uddin', 'Md. Karim Hassan'],
                'correspondingAuthor' => 'Dr. Rahman Ahmed',
                'journal' => 'IEEE Transactions on Learning Technologies',
                'journalRank' => 'Q1',
                'impactFactor' => 3.87,
                'publishDate' => '2024-02-15',
                'volume' => '17',
                'issue' => '3',
                'pages' => '245-267',
                'doi' => '10.1109/TLT.2024.1234567',
                'category' => 'Artificial Intelligence',
                'keywords' => ['Deep Learning', 'Educational Data Mining', 'Student Performance', 'Machine Learning'],
                'citations' => 156,
                'downloads' => 2340,
                'openAccess' => true,
                'featured' => true,
                'fallbackGradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'pdfUrl' => '#',
                'journalUrl' => '#'
            ],
            [
                'id' => 2,
                'title' => 'Quantum-Inspired Optimization Algorithms for Resource Allocation in Cloud Computing Environments',
                'abstract' => 'We propose novel quantum-inspired algorithms that significantly improve resource allocation efficiency in distributed cloud computing systems, achieving up to 35% better performance.',
                'authors' => ['Prof. Fatima Khan', 'Dr. Sarah Ahmed', 'Rashida Begum'],
                'correspondingAuthor' => 'Prof. Fatima Khan',
                'journal' => 'Journal of Parallel and Distributed Computing',
                'journalRank' => 'Q1',
                'impactFactor' => 4.21,
                'publishDate' => '2024-01-28',
                'volume' => '189',
                'issue' => null,
                'pages' => '104-123',
                'doi' => '10.1016/j.jpdc.2024.104123',
                'category' => 'Cloud Computing',
                'keywords' => ['Quantum Computing', 'Resource Allocation', 'Cloud Computing', 'Optimization'],
                'citations' => 89,
                'downloads' => 1876,
                'openAccess' => false,
                'featured' => true,
                'fallbackGradient' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'pdfUrl' => '#',
                'journalUrl' => '#'
            ],
            [
                'id' => 3,
                'title' => 'Blockchain-Based Secure Framework for IoT Device Authentication and Data Integrity',
                'abstract' => 'This research introduces a novel blockchain framework that ensures secure authentication and data integrity for IoT devices in smart city applications.',
                'authors' => ['Dr. Nasir Uddin', 'Md. Karim Hassan', 'Dr. Rahman Ahmed'],
                'correspondingAuthor' => 'Dr. Nasir Uddin',
                'journal' => 'Computer Networks',
                'journalRank' => 'Q1',
                'impactFactor' => 4.89,
                'publishDate' => '2023-12-10',
                'volume' => '236',
                'issue' => null,
                'pages' => '109912',
                'doi' => '10.1016/j.comnet.2023.109912',
                'category' => 'Cybersecurity',
                'keywords' => ['Blockchain', 'IoT Security', 'Authentication', 'Data Integrity'],
                'citations' => 203,
                'downloads' => 3120,
                'openAccess' => true,
                'featured' => true,
                'fallbackGradient' => 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'pdfUrl' => '#',
                'journalUrl' => '#'
            ]
        ];



        $defaultFooterData = [
            'universityName' => 'MBSTU',
            'universityFullName' => 'Mawlana Bhashani Science and Technology University',
            'universitySlogan' => 'Mawlana Bhashani Science and Technology University is aimed to provide its students with the knowledge of proper combination of science and technology...', // Truncated for brevity
            'logoUrl' => '/images/university/logo/MBSTU_logo.png',
            'address' => 'Santosh, Tangail-1902, Bangladesh',
            'phone' => '+880-921-55399',
            'email' => 'registrar@mbstu.ac.bd',
            'academicLinksTitle' => 'Academics',
            'academicLinks' => [
                ['text' => 'Academic Programs', 'href' => '/academics'],
                ['text' => 'Faculty of Engineering', 'href' => '/faculty-of-engineering'],
                ['text' => 'Faculty of Life Science', 'href' => '/faculty-of-life-science'],
                ['text' => 'Faculty of Science', 'href' => '/faculty-of-science'],
                ['text' => 'Faculty of Business Studies', 'href' => '/faculty-of-business-studies'],
                ['text' => 'Faculty of Social Science', 'href' => '/faculty-of-social-science'],
                ['text' => 'Faculty of Arts', 'href' => '/faculty-of-arts'],
            ],
            'usefulLinksTitle' => 'Useful Links',
            'usefulLinks' => [
                 ['text' => 'ICT Cell', 'href' => '/ict-cell'],
                 ['text' => 'BNCC', 'href' => '/bncc'],
                 ['text' => 'Central Library', 'href' => '/central-library'],
                 ['text' => 'Residential Halls', 'href' => '/residential-halls'],
                 ['text' => 'Students\' Welfare', 'href' => '/students-welfare-and-counseling'],
                 ['text' => 'Registrar Office', 'href' => '/registrar-office'],
                 ['text' => 'Research Cell', 'href' => '/research-cell'],
            ],
            'socialLinks' => [
                ['platform' => 'facebook', 'href' => 'https://facebook.com/mbstu'],
                ['platform' => 'twitter', 'href' => 'https://twitter.com/mbstu'],
                ['platform' => 'linkedin', 'href' => 'https://linkedin.com/company/mbstu'],
                ['platform' => 'youtube', 'href' => 'https://youtube.com/@mbstu'],
                ['platform' => 'website', 'href' => 'https://mbstu.ac.bd'],
            ],
            'copyrightText' => '© ' . date('Y') . ' Mawlana Bhashani Science and Technology University',
            'liaisonOfficeTitle' => 'Dhaka Liaison Office:',
            'liaisonOfficeAddress' => 'Alamin Apon Heights, Flat No. C-12, D-12, 2/1/B Road 03, Shaymoli, Dhaka',
        ];


        // Create sample sites
        $sites = [
            [
                'name' => 'MBSTU Main Website',
                'description' => 'Main university website for Mawlana Bhashani Science and Technology University',
                'domain' => 'localhost',
                'subdomain' => null,
                'theme_id' => 1,
                'theme_name' => 'default',
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'topBarLinks' => $defaultTopBarLinks,
                    'loginLink' => $defaultLoginLink,
                    'contactEmail' => 'info@mbstu.ac.bd',
                    'address' => 'Santosh, Tangail-1902, Bangladesh',
                    'heroSlides' => $defaultHeroSlides,
                    'headlines' => $defaultHeadlines,
                    'messageFromItems' => $defaultMessageFromItems,
                    'facultyItems' => $defaultFacultyItems,
                    'welcomeItems' => $defaultWelcomeItems,
                    'campusLifeItems' => $defaultCampusLifeItems,
                    'glanceItems' => $defaultGlanceItems,
                    'newsItems' => $defaultNewsItems,
                    'noticeItems' => $defaultNoticeItems,
                    'eventItems' => $defaultEventItems,
                    'publicationItems' => $defaultPublicationItems,
                    'footerData' => $defaultFooterData,
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
                'theme_name' => 'default',
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
                'theme_name' => 'default',
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
                'theme_name' => 'default',
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'loginLink' => $defaultLoginLink,
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
                'theme_name' => 'default',
                'is_active' => true,
                'settings' => [
                    'menuItems' => $defaultMenuItems,
                    'siteTitle' => 'MBSTU - Development',
                    'siteDescription' => 'Development environment for MBSTU website',
                    'contactEmail' => 'dev@mbstu.ac.bd',
                    'environment' => 'development',
                    'footerData' => $defaultFooterData,
                ],
                'created_by' => 1,
                'updated_by' => 1,
            ]
        ];

        // Insert sites
        foreach ($sites as $siteData) {
            // Check if a site with this domain already exists
            $existingSite = Site::where('domain', $siteData['domain'])->first();

            if ($existingSite) {
                // Update the existing site with new data
                $existingSite->update($siteData);
                $this->command->info("Updated existing site: {$siteData['name']}");
            } else {
                // Create a new site
                Site::create($siteData);
                $this->command->info("Created new site: {$siteData['name']}");
            }
        }

        $this->command->info('Sites seeded successfully!');
        $this->command->info('Created ' . count($sites) . ' sites with menu configurations.');
    }
}
