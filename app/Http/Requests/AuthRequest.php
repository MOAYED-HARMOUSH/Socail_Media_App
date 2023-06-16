<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $date = now()->subYears(12);
        $specialty = ['AI', 'Software', 'Cyber Security', 'Network'];
        $section = ['Frontend',
        'Backend',
        'MobileDevelopment',
        'FullStack',
        'DBAnalysis',
        'DevOps',
        'GamesDevelopment',
        'SoftwareAnalysis',
        'QualityAssurance',
        'CloudArchitecture',
        'MachineLearning',
        'DeepLearning',
        'Robotics',
        'NaturalLanguageProcessing',
        'ExpertSystems',
        'FuzzyLogic',
        'ComputerVision',
        'DataScientist',
        'ArtificialGeneralIntelligence',
        'NaturalNetwork',
        'NetworkSecurity',
        'CloudSecurity',
        'EndPointSecurity',
        'MobileSecurity',
        'IoTSecurity',
        'ApplicationSecurity',
        'ZeroTrust',
        'NetworkAdministrators',
        'NetworkEngineer',
        'NetworkAnalyst',
        'SystemsAdministrators',
        'NetworkTechnician',];
        $framework=[
            'Flutter',
            'AngularJs',
            'ReactJs',
            'Laravel',
            'ReactNative',
            'Net',
            'jQuery',
            'Asp',
            'Django',
            'Xamarin',
            'BootStrap',
        ];
        $language=[
            'Dart',
                'Php',
                'Java',
                'Cpp',
                'Python',
                'JavaScript',
                'TypeScript',
                'SQL',
                'Kotlin',
                'Swift',
                'Rust',
                'MatLab',
                'Scala',
        ];
        return [
            'first_name' => 'bail|required|string|alpha',
            'last_name' => 'bail|required|string|alpha',
            'email' => 'bail|required|email',
            'password' => 'bail|required|confirmed|string|min:8',
            'image_path' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
            'current_location' => 'bail|required|string',
            'gender' => 'bail|required|string|in:male,female',
            'birth_date' => "bail|required|date|before_or_equal:$date",
            'programming_age' => "bail|required|date|before_or_equal:$date",
            'specialty' => ['bail', 'required', Rule::in($specialty)],
            'language'=> ['bail', 'required', Rule::in($language)],
            'framework'=>['bail', 'required', Rule::in($framework)],
            'section' => ['bail', 'required', Rule::in($section)]
        ];
    }
}
