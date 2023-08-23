<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Avatar -->
        <div class="mt-4">
            <x-input-label for="image" :value="__('Upload your Image')" />

            <x-text-input id="image" class="block mt-1 w-full" type="file" name="image" :value="old('image')"
                accept="image/*" autocomplete="photo" />

            <x-input-error :messages="$errors->get('image')" class="mt-2" />
        </div>

        <!-- first name -->
        <div class="mt-4">
            <x-input-label for="first_name" :value="__('first_name')" />
            <x-text-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
        </div>

        <!-- last name -->
        <div class="mt-4">
            <x-input-label for="last_name" :value="__('last_name')" />
            <x-text-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')"
                required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label :value="__('I am:')" />
            <div class="mt-1 w-full">
                <label for="male" class="font-medium text-sm text-gray-700 dark:text-gray-300">male</label>
                <input type="radio" name="gender" id="male" value="male">
            </div>
            <div class="mt-1 w-full">
                <label for="female" class="font-medium text-sm text-gray-700 dark:text-gray-300">female</label>
                <input type="radio" name="gender" id="female" value="female">
            </div>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Current Location -->
        <div class="mt-4">
            <x-input-label for="current_location" :value="__('I live in:')" />
            <x-text-input id="current_location" class="block mt-1 w-full" type="text" name="current_location" :value="old('current_location')"
                required autocomplete="country-name" />
            <x-input-error :messages="$errors->get('current_location')" class="mt-2" />
        </div>

        <!-- Birth Date -->
        <div class="mt-4">
            <x-input-label for="birth_date" :value="__('Birth Date')" />
            <x-text-input id="birth_date" class="block mt-1 w-full" type="date" name="birth_date" required :value="old('birth_date')"
                autocomplete="bday" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        <!-- Programming Age -->
        <div class="mt-4">
            <x-input-label for="programming_age" :value="__('How Long you practise Programming?')" />
            <x-text-input id="programming_age" class="block mt-1 w-full" type="date" name="programming_age" required :value="old('programming_age')"
                autocomplete="bday" />
            <x-input-error :messages="$errors->get('programming_age')" class="mt-2" />
        </div>

        <!-- Specialty -->
        <div class="mt-4">
            <x-input-label :value="__('My Specialty')" />
            <div class="block mt-1 w-full">
                <label for="AI" class="font-medium text-sm text-gray-700 dark:text-gray-300">AI</label>
                <input type="radio" name="specialty" id="AI" value="AI">
            </div>
            <div class="block mt-1 w-full">
                <label for="software" class="font-medium text-sm text-gray-700 dark:text-gray-300">Software</label>
                <input type="radio" name="specialty" id="software" value="Software">
            </div>
            <div class="block mt-1 w-full">
                <label for="Cyber Security" class="font-medium text-sm text-gray-700 dark:text-gray-300">Cyber
                    Security</label>
                <input type="radio" name="specialty" id="Cyber Security" value="Cyber Security">
            </div>
            <div class="block mt-1 w-full">
                <label for="Network" class="font-medium text-sm text-gray-700 dark:text-gray-300">Network</label>
                <input type="radio" name="specialty" id="Network" value="Network">
            </div>
            <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
        </div>

        <!-- Languages -->
        <div class="mt-4">
            <x-input-label for="language" :value="__('I code with:')" />
            <select name="language" id="language" class="block mt-1 w-full text-black-700 dark:text-black-300"
                multiple required>
                <option value="Dart">Dart</option>
                <option value="Php">PHP</option>
                <option value="Cpp">C++</option>
                <option value="Python">Python</option>
                <option value="Java">Java</option>
                <option value="JavaScript">JavaScript</option>
                <option value="TypeScript">TypeScript</option>
                <option value="SQL">SQL</option>
                <option value="Kotlin">Kotlin</option>
                <option value="Swift">Swift</option>
                <option value="Rust">Rust</option>
                <option value="MatLab">MatLab</option>
                <option value="Scala">Scala</option>
            </select>
            <x-input-error :messages="$errors->get('language')" class="mt-2" />
        </div>

        <!-- Frameworks -->
        <div class="mt-4">
            <x-input-label for="framework" :value="__('I use:')" />
            <select name="framework" id="framework" class="block mt-1 w-full text-black-700 dark:text-black-300"
                multiple required>
                <option value="BootStrap">BootStrap</option>
                <option value="Xamarin">Xamarin</option>
                <option value="Django">Django</option>
                <option value="Net">Net</option>
                <option value="Asp">Asp</option>
                <option value="jQuery">jQuery</option>
                <option value="ReactNative">ReactNative</option>
                <option value="Laravel">Laravel</option>
                <option value="ReactJs">ReactJs</option>
                <option value="AngularJs">AngularJs</option>
                <option value="Flutter">Flutter</option>
            </select>
            <x-input-error :messages="$errors->get('framework')" class="mt-2" />
        </div>

        <!-- Sections -->
        <div class="mt-4">
            <x-input-label for="section" :value="__('I do:')" />
            <select name="section" id="section" class="block mt-1 w-full text-black-700 dark:text-black-300"
                multiple required>
                <option value="NetworkTechnician">NetworkTechnician</option>
                <option value="SystemsAdministrators">SystemsAdministrators</option>
                <option value="NetworkAnalyst">NetworkAnalyst</option>
                <option value="NetworkEngineer">NetworkEngineer</option>
                <option value="NetworkAdministrators">NetworkAdministrators</option>
                <option value="ZeroTrust">ZeroTrust</option>
                <option value="ApplicationSecurity">ApplicationSecurity</option>
                <option value="IoTSecurity">IoTSecurity</option>
                <option value="MobileSecurity">MobileSecurity</option>
                <option value="CloudSecurity">CloudSecurity</option>
                <option value="EndPointSecurity">EndPointSecurity</option>
                <option value="NetworkSecurity">NetworkSecurity</option>
                <option value="NaturalNetwork">NaturalNetwork</option>
                <option value="ArtificialGeneralIntelligence">ArtificialGeneralIntelligence</option>
                <option value="DataScientist">DataScientist</option>
                <option value="ComputerVision">ComputerVision</option>
                <option value="FuzzyLogic">FuzzyLogic</option>
                <option value="ExpertSystems">ExpertSystems</option>
                <option value="NaturalLanguageProcessing">NaturalLanguageProcessing</option>
                <option value="Robotics">Robotics</option>
                <option value="DeepLearning">DeepLearning</option>
                <option value="MachineLearning">MachineLearning</option>
                <option value="CloudArchitecture">CloudArchitecture</option>
                <option value="QualityAssurance">QualityAssurance</option>
                <option value="SoftwareAnalysis">SoftwareAnalysis</option>
                <option value="GamesDevelopment">GamesDevelopment</option>
                <option value="DevOps">DevOps</option>
                <option value="DBAnalysis">DBAnalysis</option>
                <option value="FullStack">FullStack</option>
                <option value="MobileDevelopment">MobileDevelopment</option>
                <option value="Backend">Backend</option>
                <option value="Frontend">Frontend</option>
            </select>
            <x-input-error :messages="$errors->get('section')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
