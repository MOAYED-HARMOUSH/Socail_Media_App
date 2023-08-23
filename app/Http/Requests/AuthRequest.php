<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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
        $now = now();
        $programming_age = Carbon::parse($this->programming_age)->subYears(12);

        $specialty = ['AI', 'Software', 'Cyber Security', 'Network'];
        $section = [
            'Frontend',
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
            'NetworkTechnician',
        ];
        $framework = [
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
        $language = [
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
            // 'first_name' => 'bail|required|string|alpha',
            // 'last_name' => 'bail|required|string|alpha',
            // 'email' => 'bail|required|string|email|max:255|unique:users',
            // 'password' => ['required', 'confirmed', Password::defaults()],
            // 'image' => 'bail|nullable|image|mimes:jpg,bmp,png,svg,jpeg',
            // 'current_location' => 'bail|required|string',
            // 'gender' => 'bail|required|in:male,female',
            'birth_date' => "bail|required|date|before_or_equal:$programming_age",
            'programming_age' => "bail|required|date|before_or_equal:$now",
            // 'specialty' => ['bail', 'required', Rule::in($specialty)],
            // 'language' => ['bail', 'required', Rule::in($language)],
            // 'framework' => ['bail', 'required', Rule::in($framework)],
            // 'section' => ['bail', 'required', Rule::in($section)]
        ];
    }
}
