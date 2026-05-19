<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin ────────────────────────────────────────────────────────
        User::create([
            'name'     => 'Rishi Kumar',
            'email'    => 'rishi.kumar14125@gmail.com',
            'password' => Hash::make('12345678'),
            'role'     => 'admin',
            'phone'    => '+91 98000 00001',
            'status'   => 'active',
        ]);

        // ─── Doctors ──────────────────────────────────────────────────────
        $doctorData = [
            [
                'name' => 'Dr. Aanya Sharma', 'email' => 'doctor@healthcare.com',
                'phone' => '+91 98000 00002',
                'specialization' => 'Cardiologist', 'qualification' => 'MBBS, MD (Cardiology)',
                'experience_years' => 12, 'consultation_fee' => 800,
                'bio' => 'Expert cardiologist with 12 years of experience in heart disease management.',
            ],
            [
                'name' => 'Dr. Rohan Mehta', 'email' => 'drrohan@healthcare.com',
                'phone' => '+91 98000 00003',
                'specialization' => 'Neurologist', 'qualification' => 'MBBS, DM (Neurology)',
                'experience_years' => 8, 'consultation_fee' => 1000,
                'bio' => 'Specialist in neurological disorders and brain health.',
            ],
            [
                'name' => 'Dr. Priya Patel', 'email' => 'drpriya@healthcare.com',
                'phone' => '+91 98000 00004',
                'specialization' => 'General Physician', 'qualification' => 'MBBS, MD',
                'experience_years' => 6, 'consultation_fee' => 500,
                'bio' => 'Experienced general physician providing comprehensive primary healthcare.',
            ],
            [
                'name' => 'Dr. Stephy Mishra', 'email' => 'stephy.mishra@healthnest.com',
                'phone' => '+91 98000 00005',
                'specialization' => 'Physiotherapist', 'qualification' => 'MBBS',
                'experience_years' => 5, 'consultation_fee' => 500,
                'bio' => 'Experienced physiotherapist specializing in sports injuries and rehabilitation.',
            ],
            [
                'name' => 'Dr. Sunita Rao', 'email' => 'drsarah@healthcare.com',
                'phone' => '+91 98000 00006',
                'specialization' => 'Dermatologist', 'qualification' => 'MBBS, MD (Dermatology)',
                'experience_years' => 9, 'consultation_fee' => 600,
                'bio' => 'Specialist in clinical dermatology, skin surgeries, and cosmetic treatments.',
            ],
            [
                'name' => 'Dr. Manoj Kumar', 'email' => 'drmichael@healthcare.com',
                'phone' => '+91 98000 00007',
                'specialization' => 'Pediatrician', 'qualification' => 'MBBS, MD (Pediatrics)',
                'experience_years' => 10, 'consultation_fee' => 700,
                'bio' => 'Dedicated pediatrician offering comprehensive care for infants, children, and adolescents.',
            ],
            [
                'name' => 'Dr. Esha Divan', 'email' => 'drelena@healthcare.com',
                'phone' => '+91 98000 00008',
                'specialization' => 'Gynecologist', 'qualification' => 'MBBS, MS (Obstetrics & Gynecology)',
                'experience_years' => 11, 'consultation_fee' => 800,
                'bio' => 'Expert in maternal-fetal medicine, prenatal care, and gynecological surgeries.',
            ],
            [
                'name' => 'Dr. Jagdish Chandra', 'email' => 'drjames@healthcare.com',
                'phone' => '+91 98000 00009',
                'specialization' => 'Orthopedist', 'qualification' => 'MBBS, MS (Orthopedics)',
                'experience_years' => 8, 'consultation_fee' => 900,
                'bio' => 'Specializes in joint replacements, sports injuries, and musculoskeletal disorders.',
            ],
            [
                'name' => 'Dr. Ekta Kapoor', 'email' => 'dremily@healthcare.com',
                'phone' => '+91 98000 00010',
                'specialization' => 'Psychiatrist', 'qualification' => 'MBBS, MD (Psychiatry)',
                'experience_years' => 7, 'consultation_fee' => 1000,
                'bio' => 'Compassionate care for anxiety, depression, mood disorders, and psychological well-being.',
            ],
            [
                'name' => 'Dr. Devashish Vyas', 'email' => 'drdavid@healthcare.com',
                'phone' => '+91 98000 00011',
                'specialization' => 'Ophthalmologist', 'qualification' => 'MBBS, MS (Ophthalmology)',
                'experience_years' => 6, 'consultation_fee' => 600,
                'bio' => 'Specialist in eye surgery, vision correction, and treatment of glaucoma and cataracts.',
            ],
            [
                'name' => 'Dr. Alisha Chinoy', 'email' => 'dralisha@healthcare.com',
                'phone' => '+91 98000 00012',
                'specialization' => 'Dentist', 'qualification' => 'BDS, MDS (Orthodontics)',
                'experience_years' => 5, 'consultation_fee' => 500,
                'bio' => 'Dedicated orthodontist providing advanced dental alignments, cleanings, and oral hygiene.',
            ],
            [
                'name' => 'Dr. Rajesh Lokhande', 'email' => 'drrobert@healthcare.com',
                'phone' => '+91 98000 00013',
                'specialization' => 'ENT Specialist', 'qualification' => 'MBBS, MS (ENT)',
                'experience_years' => 8, 'consultation_fee' => 700,
                'bio' => 'Expert treatment for ear, nose, throat, head and neck ailments.',
            ],
        ];

        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        $doctors = [];

        foreach ($doctorData as $i => $data) {
            $cleanName = preg_replace('/^(Dr\.?|Doctor)\s+/i', '', $data['name']);
            $user = User::create([
                'name' => $cleanName, 'email' => $data['email'],
                'password' => Hash::make('12345678'),
                'role' => 'doctor', 'phone' => $data['phone'], 'status' => 'active',
            ]);

            $doctor = Doctor::create([
                'user_id' => $user->id,
                'specialization' => $data['specialization'],
                'qualification' => $data['qualification'],
                'experience_years' => $data['experience_years'],
                'consultation_fee' => $data['consultation_fee'],
                'bio' => $data['bio'],
                'status' => 'active',
            ]);

            // Availability: Mon–Fri available
            foreach ($days as $day) {
                DoctorAvailability::create([
                    'doctor_id'    => $doctor->id,
                    'day_of_week'  => $day,
                    'start_time'   => '09:00',
                    'end_time'     => '17:00',
                    'is_available' => in_array($day, ['Monday','Tuesday','Wednesday','Thursday','Friday']),
                ]);
            }

            $doctors[] = $doctor;
        }

        // ─── Patients, Appointments, and Invoices Seeding ─────────────────
        $patientNames = [
            'Aarav Patel', 'Vihaan Sharma', 'Aditya Verma', 'Muhammad Khan', 'Arjun Gupta',
            'Sai Prasad', 'Reyansh Malhotra', 'Krishna Das', 'Ishaan Nair', 'Shaurya Sen',
            'Diya Joshi', 'Ananya Roy', 'Pari Saxena', 'Anika Bose', 'Aadhya Iyer',
            'Peehu Choudhury', 'Kavya Pillai', 'Saanvi Hegde', 'Riya Reddy', 'Angel Fernandez',
            'Rahul Singhal', 'Amit Trivedi', 'Sanjay Dutt', 'Rohan Gavaskar', 'Vijay Kapoor',
            'Priyanka Chopra', 'Deepika Padukone', 'Alia Bhatt', 'Kareena Kapoor', 'Katrina Kaif',
            'Karan Johar', 'Ranbir Kapoor', 'Varun Dhawan', 'Sidharth Malhotra', 'Ayushmann Khurrana',
            'Shraddha Kapoor', 'Kriti Sanon', 'Kiara Advani', 'Sara Ali Khan', 'Janhvi Kapoor'
        ];

        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $genders = ['male', 'female'];
        $reasons = [
            'Regular medical check-up', 'Sudden fever and headache', 'Consultation for stomach pain',
            'Blood pressure monitoring', 'Skin allergy and rash check', 'Knee joint pain relief',
            'Eye strain assessment', 'Routine dental cleaning', 'ENT throat allergy follow-up',
            'Anxiety and sleep management'
        ];

        $diagnoses = [
            'Mild hypertension detected', 'Acute viral nasopharyngitis', 'Gastroenteritis',
            'Vitamin D deficiency', 'Allergic dermatitis', 'Mild knee osteoarthritis'
        ];
        
        $treatments = [
            'Advised daily exercise and low salt diet.', 'Paracetamol 500mg as needed, rest and hydration.',
            'ORS fluids and light diet for 3 days.', 'Vitamin D3 60k weekly for 8 weeks.',
            'Loratadine 10mg daily for 5 days.', 'Physiotherapy sessions twice a week.'
        ];

        $patientIndex = 0;
        $patients = [];

        foreach ($doctors as $doctor) {
            // Create 3 patients for each doctor
            $docPatients = [];
            for ($p = 0; $p < 3; $p++) {
                $name = $patientNames[$patientIndex % count($patientNames)];
                $email = 'patient.' . strtolower(str_replace(' ', '', $name)) . '.' . $doctor->id . $p . '@healthnest.com';
                $gender = $genders[$patientIndex % count($genders)];
                $bloodGroup = $bloodGroups[$patientIndex % count($bloodGroups)];
                $dob = now()->subYears(rand(18, 65))->subDays(rand(1, 365))->format('Y-m-d');
                $patientIndex++;

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make('12345678'),
                    'role' => 'patient',
                    'phone' => '+91 ' . rand(7000000000, 9999999999),
                    'status' => 'active',
                ]);

                $patient = Patient::create([
                    'user_id' => $user->id,
                    'date_of_birth' => $dob,
                    'gender' => $gender,
                    'blood_group' => $bloodGroup,
                    'address' => rand(1, 150) . ', MG Road, Sector ' . rand(1, 15) . ', New Delhi',
                    'medical_history' => collect(['None', 'Mild Asthma', 'Thyroid', 'Gastric'])->random(),
                    'emergency_contact_name' => 'Spouse/Parent',
                    'emergency_contact_phone' => '+91 ' . rand(7000000000, 9999999999),
                ]);

                $docPatients[] = $patient;
                $patients[] = $patient;
            }

            // 1. Create a Pending Appointment (Patient 1)
            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $docPatients[0]->id,
                'appointment_date' => now()->addDays(rand(1, 10))->format('Y-m-d'),
                'appointment_time' => '10:00',
                'status' => 'pending',
                'reason' => $reasons[array_rand($reasons)],
            ]);

            // 2. Create an Approved Appointment (Patient 2)
            Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $docPatients[1]->id,
                'appointment_date' => now()->addDays(rand(1, 10))->format('Y-m-d'),
                'appointment_time' => '11:30',
                'status' => 'approved',
                'reason' => $reasons[array_rand($reasons)],
            ]);

            // 3. Create a Completed Appointment with PENDING FEE / UNPAID INVOICE (Patient 3)
            $apptCompletedUnpaid = Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $docPatients[2]->id,
                'appointment_date' => now()->subDays(rand(1, 5))->format('Y-m-d'),
                'appointment_time' => '14:00',
                'status' => 'completed',
                'reason' => $reasons[array_rand($reasons)],
            ]);

            $diagIdx = array_rand($diagnoses);
            Consultation::create([
                'appointment_id' => $apptCompletedUnpaid->id,
                'diagnosis' => $diagnoses[$diagIdx],
                'treatment' => $treatments[$diagIdx],
                'notes' => 'Follow up in one week.',
                'follow_up_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

            \App\Models\Invoice::create([
                'patient_id' => $docPatients[2]->id,
                'appointment_id' => $apptCompletedUnpaid->id,
                'amount' => $doctor->consultation_fee ?? 500,
                'status' => 'unpaid',
                'issued_date' => now()->subDays(1),
                'due_date' => now()->addDays(6),
            ]);

            // 4. Create a Completed Appointment with PAID FEE / PAID INVOICE (Patient 1)
            $apptCompletedPaid = Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $docPatients[0]->id,
                'appointment_date' => now()->subDays(rand(6, 15))->format('Y-m-d'),
                'appointment_time' => '15:30',
                'status' => 'completed',
                'reason' => $reasons[array_rand($reasons)],
            ]);

            Consultation::create([
                'appointment_id' => $apptCompletedPaid->id,
                'diagnosis' => 'Routine follow up checkup.',
                'treatment' => 'Continue previous prescription.',
                'notes' => 'Health condition stable.',
                'follow_up_date' => now()->addDays(30)->format('Y-m-d'),
            ]);

            $invoicePaid = \App\Models\Invoice::create([
                'patient_id' => $docPatients[0]->id,
                'appointment_id' => $apptCompletedPaid->id,
                'amount' => $doctor->consultation_fee ?? 500,
                'status' => 'paid',
                'issued_date' => now()->subDays(10),
                'due_date' => now()->subDays(3),
            ]);

            \App\Models\Payment::create([
                'invoice_id' => $invoicePaid->id,
                'amount' => $doctor->consultation_fee ?? 500,
                'payment_method' => 'online',
                'transaction_id' => 'TXN' . rand(100000000, 999999999),
                'payment_date' => now()->subDays(10),
            ]);
        }

        // ─── Complaints ───────────────────────────────────────────────────
        $complaintTypes = ['complaint', 'change_request', 'feedback'];
        $complaintStatuses = ['pending', 'in_review', 'resolved'];
        for ($i = 0; $i < 10; $i++) {
            $status = $complaintStatuses[array_rand($complaintStatuses)];
            \App\Models\Complaint::create([
                'patient_id' => $patients[array_rand($patients)]->id,
                'subject'    => 'Test Complaint ' . ($i + 1),
                'message'    => 'This is a sample message for testing the complaints and requests functionality.',
                'type'       => $complaintTypes[array_rand($complaintTypes)],
                'status'     => $status,
                'admin_response' => $status === 'resolved' ? 'This has been addressed.' : null,
                'resolved_at'    => $status === 'resolved' ? now() : null,
            ]);
        }
    }
}
