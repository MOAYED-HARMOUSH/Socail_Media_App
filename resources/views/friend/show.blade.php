<x-app-layout>
    <ul>
        @foreach ($friends as $friend)
            <li>
                <a href="/user">
                    {{ $friend->first_name }}
                    {{ $friend->last_name }}
                    {{ $friend->programming_age }}
                    {{ $friend->getFirstMedia('avatars') }}
                </a>
            </li>
        @endforeach
    </ul>
</x-app-layout>
