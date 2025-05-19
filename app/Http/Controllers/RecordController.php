<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Child;
use App\Models\Record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    public function index($id_anak, Request $request)
    {
        $child = Child::where('id_anak', $id_anak)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();

        $query = Record::where('id_anak', $id_anak);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereDate('tanggal_catatan', 'like', "%{$search}%");
        }

        $records = $query->orderBy('tanggal_catatan', 'desc')->get();

        return Inertia::render('Record/Index', [
            'child' => $child,
            'records' => $records,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create($id_anak)
    {
        $child = Child::where('id_anak', $id_anak)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();

        return Inertia::render('Record/Create', [
            'child' => $child,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_anak' => 'required|exists:m_anak,id_anak',
            'tanggal_catatan' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|numeric|min:0',
            'lingkar_kepala' => 'required|numeric|min:0',
        ]);

        $child = Child::where('id_anak', $data['id_anak'])
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();

        // Calculate age in months
        $birthDate = new \DateTime($child->tgl_lahir);
        $recordDate = new \DateTime($data['tanggal_catatan']);
        $interval = $birthDate->diff($recordDate);
        $months = ($interval->y * 12) + $interval->m;
        if ($interval->d > 0) {
            $months++;
        }

        $data['umur'] = $months;

        Record::create($data);

        return redirect()->route('records.index', $data['id_anak'])
            ->with('success', 'Data perkembangan berhasil ditambahkan.');
    }

    public function edit($id_tumbuhkembang)
    {
        $record = Record::where('id_tumbuhkembang', $id_tumbuhkembang)->firstOrFail();
        $this->authorizeRecord($record);

        return Inertia::render('Record/Edit', [
            'record' => $record,
        ]);
    }

    public function update(Request $request, $id_tumbuhkembang)
    {
        $record = Record::where('id_tumbuhkembang', $id_tumbuhkembang)->firstOrFail();
        $this->authorizeRecord($record);

        $data = $request->validate([
            'tanggal_catatan' => 'required|date',
            'berat_badan' => 'required|numeric|min:0',
            'tinggi_badan' => 'required|numeric|min:0',
            'lingkar_kepala' => 'required|numeric|min:0',
        ]);

        // Get child data for birth date
        $child = Child::where('id_anak', $record->id_anak)->first();

        // Calculate age in months
        $birthDate = new \DateTime($child->tgl_lahir);
        $recordDate = new \DateTime($data['tanggal_catatan']);
        $interval = $birthDate->diff($recordDate);
        $months = ($interval->y * 12) + $interval->m;
        if ($interval->d > 0) {
            $months++;
        }

        $data['umur'] = $months;

        $record->update($data);

        return redirect()->route('records.index', $record->id_anak)
            ->with('success', 'Data perkembangan berhasil diperbarui.');
    }

    public function destroy($id_tumbuhkembang)
    {
        $record = Record::where('id_tumbuhkembang', $id_tumbuhkembang)->firstOrFail();
        $this->authorizeRecord($record);

        $record->delete();

        return back()->with('success', 'Data perkembangan berhasil dihapus.');
    }

    private function authorizeRecord(Record $record)
    {
        $child = Child::where('id_anak', $record->id_anak)
            ->where('id_pelanggan', Auth::id())
            ->firstOrFail();
    }
}
