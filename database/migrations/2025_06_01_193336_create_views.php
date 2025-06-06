<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop existing views first
        DB::statement('DROP VIEW IF EXISTS monthly_financial_summary');
        DB::statement('DROP VIEW IF EXISTS family_payment_status');

        // Monthly Financial Summary View
        DB::statement("
            CREATE VIEW monthly_financial_summary AS
            SELECT
                DATE_FORMAT(transaction_date, '%Y-%m') as period,
                YEAR(transaction_date) as year,
                MONTH(transaction_date) as month,
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expense,
                SUM(CASE WHEN type = 'income' THEN amount ELSE -amount END) as net_balance,
                COUNT(*) as transaction_count
            FROM financial_transactions
            WHERE status = 'verified'
            GROUP BY DATE_FORMAT(transaction_date, '%Y-%m'), YEAR(transaction_date), MONTH(transaction_date)
            ORDER BY period DESC
        ");

        // Family Payment Status View
        DB::statement("
            CREATE VIEW family_payment_status AS
            SELECT
                f.id as family_id,
                f.kk_number,
                f.head_of_family,
                f.house_block,
                ft.id as fee_type_id,
                ft.name as fee_type_name,
                ft.amount as fee_amount,
                YEAR(CURDATE()) as current_year,
                MONTH(CURDATE()) as current_month,
                ps.status as payment_status,
                ps.payment_date,
                ps.verified_at
            FROM families f
            CROSS JOIN fee_types ft
            LEFT JOIN payment_submissions ps ON (
                f.id = ps.family_id
                AND ft.id = ps.fee_type_id
                AND ps.period_year = YEAR(CURDATE())
                AND ps.period_month = MONTH(CURDATE())
            )
            WHERE f.status = 'active' AND ft.is_active = TRUE
            ORDER BY f.house_block, f.head_of_family, ft.name
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS monthly_financial_summary');
        DB::statement('DROP VIEW IF EXISTS family_payment_status');
    }
};
