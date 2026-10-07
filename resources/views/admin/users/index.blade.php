<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">


                <!-- Filters -->
                <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-3 mb-6">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search name or email..."
                        class="text-sm rounded border border-gray-500 bg-gray-700 text-white px-3 py-1.5 focus:outline-none w-64">
                    <select name="role"
                        class="text-sm rounded border border-gray-500 bg-gray-700 text-white px-3 py-1.5 focus:outline-none">
                        <option value="">All Roles</option>
                        <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
                        <option value="staff"    {{ request('role') === 'staff'    ? 'selected' : '' }}>Staff</option>
                        <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    </select>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-1.5 rounded">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-gray-300 self-center underline">
                        Clear
                    </a>
                </form>

                <table class="w-full text-sm text-gray-200">
                    <thead>
                        <tr class="border-b border-gray-600 text-left text-gray-400">
                            <th class="pb-3">Name</th>
                            <th class="pb-3">Email</th>
                            <th class="pb-3">Role</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3">Joined</th>
                            <th class="pb-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr class="border-b border-gray-700">
                                <td class="py-3">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="text-xs text-gray-500">(you)</span>
                                    @endif
                                </td>
                                <td class="py-3 text-gray-400">{{ $user->email }}</td>

                                <!-- Role -->
                                <td class="py-3">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST" class="flex items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role"
                                                class="text-xs rounded border border-gray-500 bg-gray-700 text-white px-2 py-1 focus:outline-none">
                                                @foreach(['customer','staff','admin'] as $r)
                                                    <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>
                                                        {{ ucfirst($r) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="text-xs bg-gray-600 hover:bg-gray-500 text-white px-2 py-1 rounded">
                                                Save
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>

                                <!-- Status badge -->
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded text-xs text-white {{ $user->status === 'active' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>

                                <td class="py-3 text-gray-400 text-xs">{{ $user->created_at->format('M d, Y') }}</td>

                                <!-- Toggle active/inactive -->
                                <td class="py-3">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                class="text-xs px-3 py-1 rounded text-white {{ $user->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6">
                    {{ $users->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
