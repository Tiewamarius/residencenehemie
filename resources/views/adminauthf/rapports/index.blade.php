@extends('adminauth.AdminDashboard')
@section('content')

<div class="w-full px-6 py-6 mx-auto">

    <h2 class="text-xl font-bold mb-4">Rapports</h2>

    <div class="flex flex-col -mx-3">
        <div class="flex-none w-full max-w-full px-3">
            <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white shadow-soft-xl rounded-2xl">
                <div class="p-0 overflow-x-auto">
                    <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
                        <thead class="align-bottom bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">ID</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Type</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Date</th>
                                <th class="px-6 py-3 font-bold text-left text-xs text-slate-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                            <tr>
                                <td class="px-6 py-3 text-sm">{{ $report->id }}</td>
                                <td class="px-6 py-3 text-sm">{{ ucfirst($report->type) }}</td>
                                <td class="px-6 py-3 text-sm">{{ $report->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3 text-sm">
                                    <a href="{{ route('admin.reports.show', $report->id) }}" class="text-blue-500 hover:underline">Voir</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $reports->links() }}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection