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
            $user = User::create([
                'name' => $data['name'], 'email' => $data['email'],
                'password' => Hash::make('password'),
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

        // ─── Patients ─────────────────────────────────────────────────────
        $patientData = [
            ['name' => 'John Patient', 'email' => 'patient@healthcare.com', 'gender' => 'male', 'blood_group' => 'O+', 'dob' => '1990-05-15'],
            ['name' => 'Sara Wilson', 'email' => 'sara@healthcare.com', 'gender' => 'female', 'blood_group' => 'A+', 'dob' => '1985-08-22'],
            ['name' => 'Amit Kumar', 'email' => 'amit@healthcare.com', 'gender' => 'male', 'blood_group' => 'B+', 'dob' => '1992-03-10'],
            ['name' => 'Priya Gupta', 'email' => 'priyag@healthcare.com', 'gender' => 'female', 'blood_group' => 'AB-', 'dob' => '1988-11-30'],
            ['name' => 'Ravi Singh', 'email' => 'ravi@healthcare.com', 'gender' => 'male', 'blood_group' => 'O-', 'dob' => '1975-07-04'],
            ['name' => 'Neha Joshi', 'email' => 'neha@healthcare.com', 'gender' => 'female', 'blood_group' => 'A-', 'dob' => '1998-01-18'],
            ['name' => 'Vikram Das', 'email' => 'vikram@healthcare.com', 'gender' => 'male', 'blood_group' => 'B-', 'dob' => '1983-09-25'],
            ['name' => 'Meera Nair', 'email' => 'meera@healthcare.com', 'gender' => 'female', 'blood_group' => 'O+', 'dob' => '1995-06-12'],
            ['name' => 'Suresh Rao', 'email' => 'suresh@healthcare.com', 'gender' => 'male', 'blood_group' => 'A+', 'dob' => '1970-04-08'],
            ['name' => 'Kavya Reddy', 'email' => 'kavya@healthcare.com', 'gender' => 'female', 'blood_group' => 'AB+', 'dob' => '2000-12-20'],
        ];

        $patients = [];
        foreach ($patientData as $data) {
            $user = User::create([
                'name' => $data['name'], 'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'patient', 'phone' => '+91 9' . rand(100000000, 999999999), 'status' => 'active',
            ]);

            $patients[] = Patient::create([
                'user_id'       => $user->id,
                'date_of_birth' => $data['dob'],
                'gender'        => $data['gender'],
                'blood_group'   => $data['blood_group'],
                'address'       => rand(1, 100) . ', Sample Street, City - ' . rand(100000, 999999),
                'medical_history' => collect(['Hypertension', 'Diabetes Type 2', 'None', 'Asthma', 'Thyroid disorder'])->random(),
                'emergency_contact_name'  => 'Emergency Contact',
                'emergency_contact_phone' => '+91 9' . rand(100000000, 999999999),
            ]);
        }

        // ─── Appointments ─────────────────────────────────────────────────
        $statuses   = ['pending', 'approved', 'completed', 'cancelled'];
        $times      = ['09:00', '09:30', '10:00', '10:30', '11:00', '14:00', '14:30', '15:00', '16:00'];
        $reasons    = [
            'Chest pain and shortness of breath', 'Regular check-up', 'Headache and dizziness',
            'Follow-up consultation', 'Fever and body ache', 'Blood pressure monitoring',
            'Joint pain in knees', 'Skin rash and itching', 'Stomach pain', 'Eye strain and headache',
        ];

        $appointments = [];
        for ($i = 0; $i < 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $date   = $status === 'completed'
                ? now()->subDays(rand(1, 30))
                : now()->addDays(rand(0, 20));

            $appt = Appointment::create([
                'doctor_id'        => $doctors[array_rand($doctors)]->id,
                'patient_id'       => $patients[array_rand($patients)]->id,
                'appointment_date' => $date->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status'           => $status,
                'reason'           => $reasons[array_rand($reasons)],
            ]);
            $appointments[] = $appt;
        }

        // ─── Specific Appointments for doctor@healthcare.com ──────────────
        for ($i = 0; $i < 4; $i++) {
            $appt = Appointment::create([
                'doctor_id'        => $doctors[0]->id, // Dr. Aanya Sharma
                'patient_id'       => $patients[array_rand($patients)]->id,
                'appointment_date' => now()->addDays(rand(1, 5))->format('Y-m-d'),
                'appointment_time' => $times[array_rand($times)],
                'status'           => 'approved',
                'reason'           => 'Specific testing appointment ' . $i,
            ]);
            $appointments[] = $appt;
        }
        
        // Add one pending for doctor@healthcare.com
        $appointments[] = Appointment::create([
            'doctor_id'        => $doctors[0]->id,
            'patient_id'       => $patients[array_rand($patients)]->id,
            'appointment_date' => now()->addDays(rand(1, 5))->format('Y-m-d'),
            'appointment_time' => '10:00',
            'status'           => 'pending',
            'reason'           => 'Pending test appointment',
        ]);

        // ─── Consultations for completed appointments ───────────────────
        $diagnoses = [
            'Hypertensive crisis — BP 160/100 mmHg',
            'Viral upper respiratory tract infection',
            'Tension-type headache, likely stress-related',
            'Type 2 Diabetes — HbA1c elevated at 8.2%',
            'Mild gastritis, no ulceration on endoscopy',
        ];
        $treatments = [
            'Prescribed Amlodipine 5mg OD. Lifestyle modification advised.',
            'Rest, fluids, Paracetamol 500mg TDS for 5 days.',
            'Ibuprofen 400mg PRN, stress management, follow-up in 2 weeks.',
            'Metformin 500mg BD, diet chart provided, repeat HbA1c in 3 months.',
            'Pantoprazole 40mg OD for 4 weeks, avoid spicy food.',
        ];

        foreach ($appointments as $appt) {
            if ($appt->status === 'completed') {
                $idx = array_rand($diagnoses);
                Consultation::create([
                    'appointment_id' => $appt->id,
                    'diagnosis'      => $diagnoses[$idx],
                    'treatment'      => $treatments[$idx],
                    'notes'          => 'Patient advised to monitor vitals daily and return if symptoms worsen.',
                    'follow_up_date' => now()->addDays(rand(7, 30))->format('Y-m-d'),
                ]);
            }
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
