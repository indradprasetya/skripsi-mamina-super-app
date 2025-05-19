<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'telp' => [
                'required',
                'string',
                'max:20',
                'unique:m_pelanggan,telp',
                function ($attribute, $value, $fail) {
                    if (! (str_starts_with($value, '0') || str_starts_with($value, '62') || str_starts_with($value, '+62'))) {
                        $fail('Nomor telepon harus dimulai dengan 0 atau 62 atau +62');
                    }
                }
            ],
            'nama' => 'required|string|max:50',
            'jenis_kelamin' => 'required',
            'email' => 'required|email|max:50|unique:m_pelanggan,email',
            'tgl_lahir' => 'required|date',
            'id_provinsi' => 'required|exists:d_provinsi,id_provinsi',
            'id_kota' => 'required|exists:d_kota,id_kota',
            'id_kecamatan' => 'required|exists:d_kecamatan,id_kecamatan',
            'id_kelurahan' => 'required|exists:d_kelurahan,id_kelurahan',
            'id_cabang' => 'nullable|exists:m_cabang,id_cabang',
            'alamat' => 'required|string|max:100',
            'password' => 'required|string|min:5|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'telp.required' => 'Nomor telepon harus diisi',
            'telp.max' => 'Nomor telepon maksimal 20 karakter',
            'telp.unique' => 'Nomor telepon sudah digunakan',
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 50 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 50 karakter',
            'email.unique' => 'Email sudah digunakan',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi',
            'tgl_lahir.date' => 'Format tanggal lahir tidak valid',
            'id_provinsi.required' => 'Provinsi harus dipilih',
            'id_provinsi.exists' => 'Provinsi tidak valid',
            'id_kota.required' => 'Kota harus dipilih',
            'id_kota.exists' => 'Kota tidak valid',
            'id_kecamatan.required' => 'Kecamatan harus dipilih',
            'id_kecamatan.exists' => 'Kecamatan tidak valid',
            'id_kelurahan.required' => 'Kelurahan harus dipilih',
            'id_kelurahan.exists' => 'Kelurahan tidak valid',
            'id_cabang.exists' => 'Cabang tidak valid',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 100 karakter',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 5 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sesuai',
        ];
    }

    protected function prepareForValidation()
    {
        // Format nomor telepon
        if ($this->has('telp')) {
            $telp = $this->telp;
            if (str_starts_with($telp, '+62')) {
                $telp = '62' . substr($telp, 3);
            } elseif (str_starts_with($telp, '0')) {
                $telp = '62' . substr($telp, 1);
            }
            $this->merge(['telp' => $telp]);
        }
    }
}
