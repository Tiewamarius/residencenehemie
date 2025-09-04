@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Utilisateurs</h2>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Ajouter un utilisateur</a>
    </div>

    <div class="flex flex-col -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">ID</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Nom</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Email</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Rôle</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-3 text-sm">{{ $user->id }}</td>
                                <td class="px-6 py-3 text-sm">{{ $user->name }}</td>
                                <td class="px-6 py-3 text-sm">{{ $user->email }}</td>
                                <td class="px-6 py-3 text-sm">{{ ucfirst($user->role) }}</td>
                                <td class="px-6 py-3 text-sm flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-500 hover:underline">Éditer</a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection