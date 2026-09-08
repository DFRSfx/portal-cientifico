<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Trim spaces from all column names
        $row = array_map('trim', $row);
        // Check if the user already exists based on 'id_ciencia_vitae'
        $existingUser = User::where('ciencia_vitae', $row["id_ciencia_vitae"])->first();

        if ($existingUser) {
            return null; // Skip user if already exists (do not insert)
        }
        $newUser = new User();
        $newUser->name = $row["nome_completo"];
        $newUser->email = $row["email"];
        $newUser->password = Hash::make($row["id_ciencia_vitae"]);
        $newUser->ciencia_vitae = $row["id_ciencia_vitae"] ;
        $newUser->type = "teacher";
        $newUser->is_admin = 0;
        $newUser->set_password_token = $this->generatePasswordToken();
        $newUser->is_active = 1;
        $newUser->is_isla = 0;

        $success = $newUser->save();
        if ($success) {
            // associates the author information with the new user
            $newUser->authorInformation()->create([
                'orcid' => "",
                'id_google_scholar' => "",
                'id_researcher' => "",
                'id_scopus_author' => "",
                "resume" => "",
                "profile_image_is_public" => 0,
                "profile_is_public" => 0
            ]);
        }
    }

    public function batchSize(): int
    {
        return 128;
    }

    private function generatePasswordToken()
    {
        $generatedHashExists = true;

        while ($generatedHashExists) {
            $generatedHash = bin2hex(random_bytes(15));

            $user = User::where("set_password_token", "=", $generatedHash)->first();

            if (!$user) {
                $generatedHashExists = false;
            }
        }

        return $generatedHash;
    }
}
