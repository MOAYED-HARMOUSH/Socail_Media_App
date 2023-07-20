<x-app-layout>
    <ul>
        @foreach ($Requests as $request)
            <li>
                <a href="/user">
                    {{ $request->period_receiver }}
                    {{ $request->first_name }}
                    {{ $request->last_name }}
                    {{ $request->programming_age }}
                    {{ $request->getFirstMedia('avatars') }}
                </a>
                <form action="/api/friends/request/accept" method="post">
                    <div class="flex items-center justify-end mt-4">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                            href="/api/friends/request/accept">
                            <input type="button" value="Accept">
                        </a>
                    </div>
                </form>
            </li>
        @endforeach
    </ul>
</x-app-layout>
