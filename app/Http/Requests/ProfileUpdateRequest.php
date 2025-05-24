<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user() ? $this->user()->id_pelanggan : null;

        return [
            'telp' => [
                'required',
                'string',
                'max:20',
                Rule::unique('m_pelanggan', 'telp')->ignore($userId, 'id_pelanggan'),
                function ($attribute, $value, $fail) {
                    if (! (str_starts_with($value, '0') || str_starts_with($value, '62') || str_starts_with($value, '+62'))) {
                        $fail('Nomor telepon harus dimulai dengan 0 atau 62 atau +62');
                    }
                }
            ],
            'jenis_kelamin' => 'required',
            'alamat' => 'required|string|max:100',
            'id_provinsi' => 'required',
            'id_kota' => 'required',
            'id_kecamatan' => 'required',
            'id_kelurahan' => 'required',
            'id_cabang' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'telp.required' => 'Nomor telepon harus diisi',
            'telp.max' => 'Nomor telepon maksimal 20 karakter',
            'telp.unique' => 'Nomor telepon sudah digunakan',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 100 karakter',
            'id_provinsi.required' => 'Provinsi harus dipilih',
            'id_provinsi.exists' => 'Provinsi tidak valid',
            'id_kota.required' => 'Kota harus dipilih',
            'id_kota.exists' => 'Kota tidak valid',
            'id_kecamatan.required' => 'Kecamatan harus dipilih',
            'id_kecamatan.exists' => 'Kecamatan tidak valid',
            'id_kelurahan.required' => 'Kelurahan harus dipilih',
            'id_kelurahan.exists' => 'Kelurahan tidak valid',
            'id_cabang.exists' => 'Cabang tidak valid',
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
