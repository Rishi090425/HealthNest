<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SymptomCheckerController extends Controller
{
    public function index()
    {
        return view('chatbot.index');
    }

    public function check(Request $request)
    {
        $message = strtolower($request->message);
        $response = "I'm not sure about those symptoms. It's always best to consult a general physician.";
        $specialist = "General Physician";

        $rules = [
            'heart|chest|palpitation' => ['Cardiologist', 'I recommend seeing a Cardiologist if you are experiencing chest pain or heart palpitations.'],
            'skin|rash|itch'          => ['Dermatologist', 'A Dermatologist can help with skin issues like rashes or persistent itching.'],
            'headache|migraine|nerve' => ['Neurologist', 'For chronic headaches or neurological symptoms, a Neurologist is the best choice.'],
            'bone|joint|fracture'     => ['Orthopedic', 'Joint pain or bone issues should be evaluated by an Orthopedic surgeon.'],
            'stomach|digestion|acid'  => ['Gastroenterologist', 'Digestive issues are best handled by a Gastroenterologist.'],
            'eyes|vision|blur'        => ['Ophthalmologist', 'An Ophthalmologist can assist with vision-related concerns.'],
            'mental|anxiety|stress'   => ['Psychiatrist', 'For mental health support, you might want to speak with a Psychiatrist or Counselor.'],
            'fever|cough|cold'        => ['General Physician', 'Common symptoms like fever or cough can be initially treated by a General Physician.'],
        ];

        foreach ($rules as $pattern => $data) {
            if (preg_match("/$pattern/i", $message)) {
                $specialist = $data[0];
                $response = $data[1];
                break;
            }
        }

        return response()->json([
            'reply' => $response,
            'specialist' => $specialist,
            'department_id' => $this->getDepartmentId($specialist)
        ]);
    }

    private function getDepartmentId($name)
    {
        // Simple mapping or DB lookup
        return \App\Models\Specialty::where('name', 'like', "%$name%")->first()?->id;
    }
}
