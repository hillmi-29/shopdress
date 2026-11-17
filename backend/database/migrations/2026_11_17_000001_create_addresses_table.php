<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone', 20)->nullable();
            $table->string('province_code', 10)->nullable();
            $table->string('province_name')->nullable();
            $table->string('regency_code', 10)->nullable();
            $table->string('regency_name')->nullable();
            $table->string('district_code', 10)->nullable();
            $table->string('district_name')->nullable();
            $table->string('village_code', 10)->nullable();
            $table->string('village_name')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('rt', 6)->nullable();
            $table->string('rw', 6)->nullable();
            $table->string('street', 500);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        // Simpan alamat lama pada kolom users.address sebagai alamat utama
        // agar tidak hilang dari halaman profil yang baru.
        DB::table('users')
            ->whereNotNull('address')
            ->where('address', '!=', '')
            ->chunkById(100, function ($users) {
                $now = now();

                $rows = $users->map(fn ($user) => [
                    'user_id' => $user->id,
                    'label' => 'Alamat Utama',
                    'recipient_name' => $user->name,
                    'recipient_phone' => $user->phone,
                    'street' => $user->address,
                    'is_default' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all();

                DB::table('addresses')->insert($rows);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
