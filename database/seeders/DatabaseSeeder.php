<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employer;
use App\Models\Jobseeker;
use App\Models\JobseekerSkill;
use App\Models\JobListing;
use App\Models\JobSkill;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure skills catalog exists and is populated from seeded data.
        // (Keeps backward compatibility with existing string-based skills tables.)
        // 1 Admin user
        User::create([
            'first_name' => 'System',
            'middle_name' => null,
            'last_name' => 'Administrator',
            'email' => 'admin@peso.gov.ph',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'sex' => 'male',
            'contact' => '09171234567',
            'address' => 'PESO Main Office, Manila',
            'status' => 'active',
        ]);

        // 3 Staff users
        $staffUsers = [
            ['first_name' => 'Maria', 'last_name' => 'Santos', 'email' => 'maria.santos@peso.gov.ph', 'sex' => 'female'],
            ['first_name' => 'Juan', 'last_name' => 'Dela Cruz', 'email' => 'juan.delacruz@peso.gov.ph', 'sex' => 'male'],
            ['first_name' => 'Ana', 'last_name' => 'Reyes', 'email' => 'ana.reyes@peso.gov.ph', 'sex' => 'female'],
        ];

        foreach ($staffUsers as $staff) {
            User::create([
                'first_name' => $staff['first_name'],
                'middle_name' => null,
                'last_name' => $staff['last_name'],
                'email' => $staff['email'],
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'sex' => $staff['sex'],
                'contact' => '09' . rand(100000000, 999999999),
                'address' => 'PESO Office, Manila',
                'status' => 'active',
            ]);
        }

        // 5 Sample employers (approved)
        $employers = [
            [
                'company_name' => 'Nexus Tech Solutions',
                'contact_person' => 'Carlo Mendoza',
                'email' => 'hr@nexustech.ph',
                'industry' => 'Information Technology',
                'company_size' => '50-200',
                'city' => 'Makati',
                'phone' => '02-8123456',
                'tin' => '123-456-789-000',
                'website' => 'https://nexustech.ph',
                'latitude' => 16.691483,
                'longitude' => 121.555038,
                'address_full' => '123 Ayala Avenue, Makati City',
            ],
            [
                'company_name' => 'Global Innovations Inc.',
                'contact_person' => 'Sarah Lim',
                'email' => 'careers@globalinnovations.com',
                'industry' => 'Software Development',
                'company_size' => '200-500',
                'city' => 'Taguig',
                'phone' => '02-8987654',
                'tin' => '234-567-890-000',
                'website' => 'https://globalinnovations.com',
                'latitude' => 16.683431,
                'longitude' => 121.552266,
                'address_full' => 'Bonifacio Global City, Taguig',
            ],
            [
                'company_name' => 'Creative Designs Studio',
                'contact_person' => 'Liza Tan',
                'city' => 'Quezon City',
                'email' => 'jobs@creativedesigns.ph',
                'industry' => 'Design & Creative',
                'company_size' => '10-50',
                'phone' => '02-7123456',
                'tin' => '345-678-901-000',
                'website' => 'https://creativedesigns.ph',
                'latitude' => 16.686836,
                'longitude' => 121.54827,
                'address_full' => '45 Maginhawa St, Quezon City',
            ],
            [
                'company_name' => 'DataStream Analytics',
                'contact_person' => 'Michael Cruz',
                'email' => 'hiring@datastream.io',
                'industry' => 'Data Analytics',
                'company_size' => '50-200',
                'city' => 'Pasig',
                'phone' => '02-6345678',
                'tin' => '456-789-012-000',
                'website' => 'https://datastream.io',
                'latitude' => 16.697412,
                'longitude' => 121.562784,
                'address_full' => 'Ortigas Center, Pasig City',
            ],
            [
                'company_name' => 'CloudScale Systems',
                'contact_person' => 'Patricia Garcia',
                'email' => 'talent@cloudscale.ph',
                'industry' => 'Cloud Computing',
                'company_size' => '500+',
                'city' => 'Mandaluyong',
                'phone' => '02-7234567',
                'tin' => '567-890-123-000',
                'website' => 'https://cloudscale.ph',
                'latitude' => 16.693109,
                'longitude' => 121.55205,
                'address_full' => 'EDSA Corner Shaw Blvd, Mandaluyong',
            ],
        ];

        $employerModels = [];
        foreach ($employers as $employerData) {
            $employerModels[] = Employer::create([
                ...$employerData,
                'password' => Hash::make('password123'),
                'status' => 'verified',
                'verified_at' => now(),
                'map_visible' => true,
            ]);
        }

        // 10 Sample jobseekers with skills and verified emails
        $jobseekersData = [
            ['first_name' => 'Sofia', 'last_name' => 'Ramos', 'email' => 'sofia.ramos@email.com', 'sex' => 'female', 'skills' => ['Vue.js', 'Nuxt', 'SCSS', 'JavaScript']],
            ['first_name' => 'Marco', 'last_name' => 'Villanueva', 'email' => 'marco.v@email.com', 'sex' => 'male', 'skills' => ['Docker', 'CI/CD', 'Linux', 'AWS']],
            ['first_name' => 'Dante', 'last_name' => 'Cruz', 'email' => 'dante.cruz@email.com', 'sex' => 'male', 'skills' => ['Scrum', 'MS Project', 'Risk Management', 'Agile']],
            ['first_name' => 'Rina', 'last_name' => 'Flores', 'email' => 'rina.flores@email.com', 'sex' => 'female', 'skills' => ['React', 'Tailwind', 'Git', 'TypeScript']],
            ['first_name' => 'Ben', 'last_name' => 'Ocampo', 'email' => 'ben.ocampo@email.com', 'sex' => 'male', 'skills' => ['Laravel', 'PHP', 'MySQL', 'Redis']],
            ['first_name' => 'Grace', 'last_name' => 'Dela Rosa', 'email' => 'grace.delarosa@email.com', 'sex' => 'female', 'skills' => ['Figma', 'Prototyping', 'Sketch', 'Adobe XD']],
            ['first_name' => 'Nico', 'last_name' => 'Santos', 'email' => 'nico.santos@email.com', 'sex' => 'male', 'skills' => ['Kubernetes', 'AWS', 'Terraform', 'Docker']],
            ['first_name' => 'Elle', 'last_name' => 'Reyes', 'email' => 'elle.reyes@email.com', 'sex' => 'female', 'skills' => ['Agile', 'Jira', 'Team Leadership', 'Scrum']],
            ['first_name' => 'Paolo', 'last_name' => 'Garcia', 'email' => 'paolo.garcia@email.com', 'sex' => 'male', 'skills' => ['Python', 'Django', 'PostgreSQL', 'Redis']],
            ['first_name' => 'Maya', 'last_name' => 'Lopez', 'email' => 'maya.lopez@email.com', 'sex' => 'female', 'skills' => ['UI/UX Design', 'Figma', 'User Research', 'Prototyping']],
        ];

        $jobseekerModels = [];
        foreach ($jobseekersData as $jsData) {
            $skills = $jsData['skills'];
            unset($jsData['skills']);

            $jobseeker = Jobseeker::create([
                ...$jsData,
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'contact' => '09' . rand(100000000, 999999999),
                'address' => 'Metro Manila, Philippines',
                'status' => 'active',
                'latitude' => 14.5995 + (rand(-100, 100) / 1000),
                'longitude' => 120.9842 + (rand(-100, 100) / 1000),
            ]);

            foreach ($skills as $skill) {
                JobseekerSkill::create([
                    'jobseeker_id' => $jobseeker->id,
                    'skill' => $skill,
                ]);
            }

            $jobseekerModels[] = $jobseeker;
        }

        // 15 Job listings
        $jobListingsData = [
            ['employer_id' => 1, 'title' => 'Senior Frontend Developer', 'type' => 'full-time', 'location' => 'Makati', 'skills' => ['Vue.js', 'JavaScript', 'CSS']],
            ['employer_id' => 1, 'title' => 'Backend Developer', 'type' => 'full-time', 'location' => 'Makati', 'skills' => ['Laravel', 'PHP', 'MySQL']],
            ['employer_id' => 1, 'title' => 'DevOps Engineer', 'type' => 'full-time', 'location' => 'Makati', 'skills' => ['AWS', 'Docker', 'Kubernetes']],
            ['employer_id' => 2, 'title' => 'Full Stack Developer', 'type' => 'full-time', 'location' => 'Taguig', 'skills' => ['React', 'Node.js', 'MongoDB']],
            ['employer_id' => 2, 'title' => 'UI/UX Designer', 'type' => 'full-time', 'location' => 'Taguig', 'skills' => ['Figma', 'UI/UX', 'Adobe XD']],
            ['employer_id' => 2, 'title' => 'Product Manager', 'type' => 'full-time', 'location' => 'Taguig', 'skills' => ['Agile', 'Scrum', 'JIRA']],
            ['employer_id' => 3, 'title' => 'Graphic Designer', 'type' => 'contract', 'location' => 'Quezon City', 'skills' => ['Adobe Photoshop', 'Illustrator', 'Figma']],
            ['employer_id' => 3, 'title' => 'Web Designer', 'type' => 'part-time', 'location' => 'Quezon City', 'skills' => ['HTML', 'CSS', 'Figma']],
            ['employer_id' => 4, 'title' => 'Data Analyst', 'type' => 'full-time', 'location' => 'Pasig', 'skills' => ['Python', 'SQL', 'Tableau']],
            ['employer_id' => 4, 'title' => 'Data Engineer', 'type' => 'full-time', 'location' => 'Pasig', 'skills' => ['Python', 'PostgreSQL', 'AWS']],
            ['employer_id' => 5, 'title' => 'Cloud Architect', 'type' => 'full-time', 'location' => 'Mandaluyong', 'skills' => ['AWS', 'Terraform', 'Kubernetes']],
            ['employer_id' => 5, 'title' => 'Site Reliability Engineer', 'type' => 'full-time', 'location' => 'Mandaluyong', 'skills' => ['Linux', 'Docker', 'CI/CD']],
            ['employer_id' => 1, 'title' => 'Mobile App Developer', 'type' => 'full-time', 'location' => 'Makati', 'skills' => ['React Native', 'JavaScript', 'Firebase']],
            ['employer_id' => 2, 'title' => 'QA Engineer', 'type' => 'full-time', 'location' => 'Taguig', 'skills' => ['Selenium', 'Testing', 'Automation']],
            ['employer_id' => 3, 'title' => 'Motion Designer', 'type' => 'contract', 'location' => 'Quezon City', 'skills' => ['After Effects', 'Cinema 4D', 'Figma']],
        ];

        $jobListingModels = [];
        foreach ($jobListingsData as $jobData) {
            $skills = $jobData['skills'];
            unset($jobData['skills']);

            $job = JobListing::create([
                ...$jobData,
                'description' => 'We are looking for a talented ' . $jobData['title'] . ' to join our team. The ideal candidate will have strong skills in ' . implode(', ', $skills) . '.',
                'salary_range' => '₱30,000 - ₱80,000',
                'slots' => rand(1, 5),
                'status' => 'open',
                'posted_date' => now()->subDays(rand(1, 30)),
                'deadline' => now()->addDays(rand(15, 60)),
            ]);

            foreach ($skills as $skill) {
                JobSkill::create([
                    'job_listing_id' => $job->id,
                    'skill' => $skill,
                ]);
            }

            $jobListingModels[] = $job;
        }

        // Seed a broad PH-relevant skill catalog first (not tech-only), then add any
        // additional skills found in seeded job listings / jobseeker profiles.
        $masterSkills = [
            // Soft skills
            ['name' => 'Communication Skills', 'category' => 'Soft Skills'],
            ['name' => 'Verbal Communication', 'category' => 'Soft Skills'],
            ['name' => 'Written Communication', 'category' => 'Soft Skills'],
            ['name' => 'Customer Service', 'category' => 'Soft Skills'],
            ['name' => 'Problem Solving', 'category' => 'Soft Skills'],
            ['name' => 'Critical Thinking', 'category' => 'Soft Skills'],
            ['name' => 'Decision Making', 'category' => 'Soft Skills'],
            ['name' => 'Time Management', 'category' => 'Soft Skills'],
            ['name' => 'Teamwork', 'category' => 'Soft Skills'],
            ['name' => 'Collaboration', 'category' => 'Soft Skills'],
            ['name' => 'Leadership', 'category' => 'Soft Skills'],
            ['name' => 'Conflict Resolution', 'category' => 'Soft Skills'],
            ['name' => 'Adaptability', 'category' => 'Soft Skills'],
            ['name' => 'Stress Management', 'category' => 'Soft Skills'],
            ['name' => 'Work Ethic', 'category' => 'Soft Skills'],
            ['name' => 'Professionalism', 'category' => 'Soft Skills'],
            ['name' => 'Attention to Detail', 'category' => 'Soft Skills'],
            ['name' => 'Creativity', 'category' => 'Soft Skills'],
            ['name' => 'Negotiation', 'category' => 'Soft Skills'],
            ['name' => 'Public Speaking', 'category' => 'Soft Skills'],
            ['name' => 'Presentation Skills', 'category' => 'Soft Skills'],
            ['name' => 'Active Listening', 'category' => 'Soft Skills'],
            ['name' => 'Empathy', 'category' => 'Soft Skills'],
            ['name' => 'Integrity', 'category' => 'Soft Skills'],
            ['name' => 'Initiative', 'category' => 'Soft Skills'],
            ['name' => 'Accountability', 'category' => 'Soft Skills'],
            ['name' => 'Multitasking', 'category' => 'Soft Skills'],

            // Office / Admin / BPO (very common PH jobs)
            ['name' => 'Data Entry', 'category' => 'Office & Admin'],
            ['name' => 'Filing and Documentation', 'category' => 'Office & Admin'],
            ['name' => 'Email Management', 'category' => 'Office & Admin'],
            ['name' => 'Calendar Management', 'category' => 'Office & Admin'],
            ['name' => 'Appointment Scheduling', 'category' => 'Office & Admin'],
            ['name' => 'Office Management', 'category' => 'Office & Admin'],
            ['name' => 'Records Management', 'category' => 'Office & Admin'],
            ['name' => 'Front Desk Operations', 'category' => 'Office & Admin'],
            ['name' => 'Reception', 'category' => 'Office & Admin'],
            ['name' => 'Administrative Support', 'category' => 'Office & Admin'],
            ['name' => 'Inventory Management', 'category' => 'Office & Admin'],
            ['name' => 'Purchasing', 'category' => 'Office & Admin'],
            ['name' => 'Procurement', 'category' => 'Office & Admin'],
            ['name' => 'Vendor Management', 'category' => 'Office & Admin'],
            ['name' => 'Microsoft Word', 'category' => 'Office & Admin'],
            ['name' => 'Microsoft Excel', 'category' => 'Office & Admin'],
            ['name' => 'Microsoft PowerPoint', 'category' => 'Office & Admin'],
            ['name' => 'Google Docs', 'category' => 'Office & Admin'],
            ['name' => 'Google Sheets', 'category' => 'Office & Admin'],
            ['name' => 'Google Slides', 'category' => 'Office & Admin'],
            ['name' => 'Typing', 'category' => 'Office & Admin'],
            ['name' => 'Transcription', 'category' => 'Office & Admin'],
            ['name' => 'Basic Computer Literacy', 'category' => 'Office & Admin'],
            ['name' => 'CRM Tools', 'category' => 'Office & Admin'],
            ['name' => 'Call Handling', 'category' => 'BPO & Customer Support'],
            ['name' => 'Outbound Calling', 'category' => 'BPO & Customer Support'],
            ['name' => 'Inbound Support', 'category' => 'BPO & Customer Support'],
            ['name' => 'Chat Support', 'category' => 'BPO & Customer Support'],
            ['name' => 'Email Support', 'category' => 'BPO & Customer Support'],
            ['name' => 'Technical Support', 'category' => 'BPO & Customer Support'],
            ['name' => 'Sales Support', 'category' => 'BPO & Customer Support'],
            ['name' => 'Collections', 'category' => 'BPO & Customer Support'],
            ['name' => 'Customer Retention', 'category' => 'BPO & Customer Support'],
            ['name' => 'Upselling', 'category' => 'BPO & Customer Support'],
            ['name' => 'Cross-selling', 'category' => 'BPO & Customer Support'],
            ['name' => 'Ticketing Systems', 'category' => 'BPO & Customer Support'],
            ['name' => 'Quality Assurance (Call Center)', 'category' => 'BPO & Customer Support'],

            // Retail / Sales
            ['name' => 'Sales', 'category' => 'Retail & Sales'],
            ['name' => 'Cash Handling', 'category' => 'Retail & Sales'],
            ['name' => 'POS Operation', 'category' => 'Retail & Sales'],
            ['name' => 'Merchandising', 'category' => 'Retail & Sales'],
            ['name' => 'Stock Replenishment', 'category' => 'Retail & Sales'],
            ['name' => 'Sales Closing', 'category' => 'Retail & Sales'],
            ['name' => 'Lead Generation', 'category' => 'Retail & Sales'],
            ['name' => 'Sales Negotiation', 'category' => 'Retail & Sales'],
            ['name' => 'Account Management', 'category' => 'Retail & Sales'],
            ['name' => 'Store Operations', 'category' => 'Retail & Sales'],
            ['name' => 'Visual Merchandising', 'category' => 'Retail & Sales'],

            // Logistics / Driving (common PH roles)
            ['name' => 'Delivery Driving', 'category' => 'Logistics & Transport'],
            ['name' => 'Truck Driving', 'category' => 'Logistics & Transport'],
            ['name' => 'Motorcycle Delivery', 'category' => 'Logistics & Transport'],
            ['name' => 'Route Planning', 'category' => 'Logistics & Transport'],
            ['name' => 'Fleet Management', 'category' => 'Logistics & Transport'],
            ['name' => 'Dispatching', 'category' => 'Logistics & Transport'],
            ['name' => 'Warehouse Operations', 'category' => 'Logistics & Transport'],
            ['name' => 'Forklift Operation', 'category' => 'Logistics & Transport'],
            ['name' => 'Inventory Control', 'category' => 'Logistics & Transport'],
            ['name' => 'Shipping and Receiving', 'category' => 'Logistics & Transport'],
            ['name' => 'Order Picking', 'category' => 'Logistics & Transport'],
            ['name' => 'Packing', 'category' => 'Logistics & Transport'],
            ['name' => 'Loading and Unloading', 'category' => 'Logistics & Transport'],
            ['name' => 'Basic Vehicle Maintenance', 'category' => 'Logistics & Transport'],

            // Hospitality / Food (big PH sector)
            ['name' => 'Food Preparation', 'category' => 'Hospitality & Food'],
            ['name' => 'Cooking', 'category' => 'Hospitality & Food'],
            ['name' => 'Kitchen Sanitation', 'category' => 'Hospitality & Food'],
            ['name' => 'Food Safety', 'category' => 'Hospitality & Food'],
            ['name' => 'HACCP Basics', 'category' => 'Hospitality & Food'],
            ['name' => 'Barista Skills', 'category' => 'Hospitality & Food'],
            ['name' => 'Baking', 'category' => 'Hospitality & Food'],
            ['name' => 'Pastry Making', 'category' => 'Hospitality & Food'],
            ['name' => 'Bartending', 'category' => 'Hospitality & Food'],
            ['name' => 'Mixology', 'category' => 'Hospitality & Food'],
            ['name' => 'Table Service', 'category' => 'Hospitality & Food'],
            ['name' => 'Guest Relations', 'category' => 'Hospitality & Food'],
            ['name' => 'Housekeeping', 'category' => 'Hospitality & Food'],
            ['name' => 'Hotel Front Office', 'category' => 'Hospitality & Food'],
            ['name' => 'Banquet Service', 'category' => 'Hospitality & Food'],
            ['name' => 'Event Setup', 'category' => 'Hospitality & Food'],

            // Healthcare / Caregiving (PH in-demand local & abroad)
            ['name' => 'Caregiving', 'category' => 'Healthcare & Care'],
            ['name' => 'Elderly Care', 'category' => 'Healthcare & Care'],
            ['name' => 'Childcare', 'category' => 'Healthcare & Care'],
            ['name' => 'First Aid', 'category' => 'Healthcare & Care'],
            ['name' => 'CPR', 'category' => 'Healthcare & Care'],
            ['name' => 'Basic Nursing Care', 'category' => 'Healthcare & Care'],
            ['name' => 'Medication Assistance', 'category' => 'Healthcare & Care'],
            ['name' => 'Vital Signs Monitoring', 'category' => 'Healthcare & Care'],
            ['name' => 'Patient Care', 'category' => 'Healthcare & Care'],
            ['name' => 'Clinical Documentation', 'category' => 'Healthcare & Care'],
            ['name' => 'Phlebotomy Basics', 'category' => 'Healthcare & Care'],
            ['name' => 'Medical Billing', 'category' => 'Healthcare & Care'],
            ['name' => 'Medical Coding', 'category' => 'Healthcare & Care'],

            // Construction / Trades (very relevant)
            ['name' => 'Carpentry', 'category' => 'Construction & Trades'],
            ['name' => 'Masonry', 'category' => 'Construction & Trades'],
            ['name' => 'Tile Setting', 'category' => 'Construction & Trades'],
            ['name' => 'Plumbing', 'category' => 'Construction & Trades'],
            ['name' => 'Electrical Installation', 'category' => 'Construction & Trades'],
            ['name' => 'Welding', 'category' => 'Construction & Trades'],
            ['name' => 'Steel Fabrication', 'category' => 'Construction & Trades'],
            ['name' => 'Rebar Works', 'category' => 'Construction & Trades'],
            ['name' => 'Scaffolding', 'category' => 'Construction & Trades'],
            ['name' => 'Painting', 'category' => 'Construction & Trades'],
            ['name' => 'Drywall Installation', 'category' => 'Construction & Trades'],
            ['name' => 'Ceiling Installation', 'category' => 'Construction & Trades'],
            ['name' => 'Concrete Works', 'category' => 'Construction & Trades'],
            ['name' => 'Site Safety', 'category' => 'Construction & Trades'],
            ['name' => 'Construction Estimation', 'category' => 'Construction & Trades'],
            ['name' => 'AutoCAD', 'category' => 'Construction & Trades'],

            // Agriculture / Fisheries
            ['name' => 'Crop Farming', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Vegetable Farming', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Rice Farming', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Livestock Care', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Poultry Raising', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Aquaculture', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Fish Handling', 'category' => 'Agriculture & Fisheries'],
            ['name' => 'Post-harvest Handling', 'category' => 'Agriculture & Fisheries'],

            // Security / Maintenance
            ['name' => 'Security Guarding', 'category' => 'Security & Maintenance'],
            ['name' => 'CCTV Monitoring', 'category' => 'Security & Maintenance'],
            ['name' => 'Incident Reporting', 'category' => 'Security & Maintenance'],
            ['name' => 'Facility Maintenance', 'category' => 'Security & Maintenance'],
            ['name' => 'Electrical Maintenance', 'category' => 'Security & Maintenance'],
            ['name' => 'Plumbing Maintenance', 'category' => 'Security & Maintenance'],
            ['name' => 'Aircon Servicing', 'category' => 'Security & Maintenance'],
            ['name' => 'Basic Troubleshooting', 'category' => 'Security & Maintenance'],
        ];

        // Expand to 300-500 skills via structured category lists (keeps it readable and maintainable).
        $generated = [];
        $byCategory = [
            'Construction & Trades' => [
                'Heavy Equipment Operation', 'Backhoe Operation', 'Excavator Operation', 'Bulldozer Operation',
                'Road Works', 'Asphalt Works', 'Surveying Basics', 'Blueprint Reading', 'Formwork', 'Finishing Works',
                'Roofing', 'Glass Installation', 'Aluminum Fabrication', 'Pipefitting', 'Fire Safety', 'OSH Compliance',
            ],
            'Hospitality & Food' => [
                'Food and Beverage Service', 'Kitchen Operations', 'Meal Planning', 'Food Costing',
                'Restaurant Operations', 'Cashiering', 'Inventory for F&B', 'Dishwashing', 'Butchery Basics',
            ],
            'Healthcare & Care' => [
                'Health and Safety Compliance', 'Infection Control', 'Basic Life Support', 'Patient Safety',
                'Home Care', 'Disability Care', 'Nutrition Assistance', 'Hygiene Assistance',
            ],
            'Retail & Sales' => [
                'Product Knowledge', 'Sales Forecasting', 'Sales Reporting', 'Customer Engagement',
                'Promotions Management', 'Retail Inventory', 'Store Cashiering', 'Returns Processing',
            ],
            'Logistics & Transport' => [
                'Supply Chain Basics', 'Logistics Coordination', 'Courier Operations', 'Waybill Processing',
                'Warehouse Safety', 'RF Scanning', 'Cycle Counting', 'Delivery Proof of Delivery',
            ],
            'Office & Admin' => [
                'Office Coordination', 'Minute Taking', 'Report Writing', 'Basic Bookkeeping',
                'Payroll Assistance', 'Petty Cash Handling', 'Document Control', 'Forms Processing',
            ],
            'Manufacturing & Production' => [
                'Machine Operation', 'Production Line Work', 'Quality Control', 'Packaging Operations',
                '5S Implementation', 'Lean Basics', 'Preventive Maintenance', 'SOP Compliance',
            ],
            'Agriculture & Fisheries' => [
                'Farm Equipment Operation', 'Irrigation', 'Pest Control', 'Soil Preparation',
                'Harvesting', 'Sorting and Grading', 'Fish Cage Maintenance', 'Net Repair',
            ],
            'Beauty & Wellness' => [
                'Haircutting', 'Hair Coloring', 'Barbering', 'Makeup Artistry', 'Manicure', 'Pedicure',
                'Facial Treatment', 'Massage Therapy', 'Spa Operations', 'Skincare Consultation',
            ],
            'Education & Training' => [
                'Lesson Planning', 'Classroom Management', 'Student Assessment', 'Tutoring',
                'Training Delivery', 'Curriculum Development', 'Learning Management Systems',
            ],
            'Finance & Accounting' => [
                'Accounts Payable', 'Accounts Receivable', 'General Ledger', 'Bank Reconciliation',
                'Financial Reporting', 'Tax Compliance Basics', 'Budgeting', 'Cost Accounting',
            ],
            'HR & Admin' => [
                'Recruitment', 'Onboarding', 'Employee Relations', 'Timekeeping',
                'HR Documentation', 'Benefits Administration', 'Training Coordination',
            ],
            'Marketing & Media' => [
                'Content Writing', 'Copywriting', 'Social Media Management', 'Community Management',
                'SEO Basics', 'Email Marketing', 'Graphic Design', 'Video Editing',
            ],
            'IT & Digital' => [
                'Computer Troubleshooting', 'Network Basics', 'Hardware Repair', 'Software Installation',
                'Cybersecurity Basics', 'Microsoft 365', 'Google Workspace', 'Database Basics',
            ],
        ];

        foreach ($byCategory as $category => $names) {
            foreach ($names as $name) {
                $generated[] = ['name' => $name, 'category' => $category];
            }
        }

        // Add a large set of common job-role skills as separate items (PH-wide, non-tech included).
        $roleBased = [
            // Construction roles
            'Foreman Skills', 'Construction Supervision', 'Site Inspection', 'Material Takeoff',
            // Logistics roles
            'Rider Safety', 'Defensive Driving', 'Traffic Rules Knowledge',
            // Hospitality roles
            'Room Attendant Skills', 'Hotel Reservation Handling',
            // Healthcare roles
            'Care Plan Implementation', 'Patient Mobility Assistance',
            // Retail roles
            'Sales Promodiser Skills', 'Retail Loss Prevention',
            // Services
            'Laundry Operations', 'Ironing', 'General Cleaning', 'Pest Control Basics',
        ];
        foreach ($roleBased as $name) {
            $generated[] = ['name' => $name, 'category' => null];
        }

        $masterSkills = array_merge($masterSkills, $generated);

        // Ensure at least ~350 skills by adding standardized office/industry tools list.
        $tools = [
            'Microsoft Outlook', 'Microsoft Teams', 'Zoom', 'Google Meet', 'Slack',
            'Trello', 'Asana', 'Jira', 'Notion', 'Canva',
            'QuickBooks', 'Xero', 'SAP Basics', 'Oracle NetSuite Basics',
        ];
        foreach ($tools as $name) {
            $masterSkills[] = ['name' => $name, 'category' => 'Tools'];
        }

        // Insert master list (idempotent)
        foreach ($masterSkills as $item) {
            $name = trim(preg_replace('/\s+/', ' ', (string) ($item['name'] ?? '')) ?? '');
            if ($name === '') continue;
            $lower = mb_strtolower($name);

            $exists = DB::table('skills')->whereRaw('LOWER(name) = ?', [$lower])->exists();
            if ($exists) continue;

            $slug = Str::slug($lower);
            if ($slug === '') $slug = Str::random(12);

            $base = $slug;
            $i = 2;
            while (DB::table('skills')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }

            DB::table('skills')->insert([
                'name' => $name,
                'slug' => $slug,
                'category' => $item['category'] ?? null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Populate skills catalog + new pivot tables from existing string-based skill rows.
        $rawSkills = collect()
            ->merge(DB::table('job_skills')->pluck('skill'))
            ->merge(DB::table('jobseeker_skills')->pluck('skill'))
            ->filter(fn ($s) => is_string($s) && trim($s) !== '')
            ->map(fn ($s) => trim(preg_replace('/\s+/', ' ', (string) $s) ?? ''))
            ->unique(fn ($s) => mb_strtolower($s))
            ->values();

        $nameToId = DB::table('skills')
            ->select('id', 'name')
            ->get()
            ->mapWithKeys(fn ($r) => [mb_strtolower((string) $r->name) => (int) $r->id])
            ->all();

        foreach ($rawSkills as $name) {
            $lower = mb_strtolower($name);
            if (isset($nameToId[$lower])) continue;

            $slug = Str::slug($lower);
            if ($slug === '') $slug = Str::random(12);

            $base = $slug;
            $i = 2;
            while (DB::table('skills')->where('slug', $slug)->exists()) {
                $slug = $base . '-' . $i;
                $i++;
            }

            $id = DB::table('skills')->insertGetId([
                'name' => $name,
                'slug' => $slug,
                'category' => null,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $nameToId[$lower] = $id;
        }

        foreach (DB::table('job_skills')->select('job_listing_id', 'skill')->get() as $row) {
            $name = trim(preg_replace('/\s+/', ' ', (string) $row->skill) ?? '');
            if ($name === '') continue;
            $skillId = $nameToId[mb_strtolower($name)] ?? null;
            if (!$skillId) continue;

            DB::table('job_listing_skill_items')->updateOrInsert(
                ['job_listing_id' => $row->job_listing_id, 'skill_id' => $skillId],
                ['is_required' => 0, 'priority' => 0, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        foreach (DB::table('jobseeker_skills')->select('jobseeker_id', 'skill')->get() as $row) {
            $name = trim(preg_replace('/\s+/', ' ', (string) $row->skill) ?? '');
            if ($name === '') continue;
            $skillId = $nameToId[mb_strtolower($name)] ?? null;
            if (!$skillId) continue;

            DB::table('jobseeker_skill_items')->updateOrInsert(
                ['jobseeker_id' => $row->jobseeker_id, 'skill_id' => $skillId],
                ['proficiency' => null, 'years_experience' => null, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // 30 Applications
        $statuses = ['reviewing', 'shortlisted', 'interview', 'hired', 'rejected'];
        $applicationsCreated = 0;

        for ($i = 0; $i < 30; $i++) {
            $jobseeker = $jobseekerModels[array_rand($jobseekerModels)];
            $jobListing = $jobListingModels[array_rand($jobListingModels)];

            // Check if application already exists
            $existing = Application::where('job_listing_id', $jobListing->id)
                ->where('jobseeker_id', $jobseeker->id)
                ->first();

            if (!$existing) {
                $matchScore = Application::calculateMatchScore($jobseeker, $jobListing);

                Application::create([
                    'job_listing_id' => $jobListing->id,
                    'jobseeker_id' => $jobseeker->id,
                    'status' => $statuses[array_rand($statuses)],
                    'match_score' => $matchScore,
                    'applied_at' => now()->subDays(rand(1, 20)),
                ]);

                $applicationsCreated++;
            }
        }

        $this->command->info("Created: 1 admin, 3 staff users, 5 employers, 10 jobseekers, 15 job listings, {$applicationsCreated} applications");

        // Seed notifications for all employers
        $this->call(NotificationSeeder::class);

        // Seed events
        $this->call(EventSeeder::class);
    }
}
