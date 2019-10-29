<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // With -> relacion entera | withCount -> conteo de estudiantes en ese curso
        $courses = Course::withCount(['students'])
            ->with('category', 'teacher', 'reviews')
            ->where('status', Course::PUBLISHED)
            ->latest()->paginate(12);

        return view('home', compact('courses'));
    }

    public function salesmen_cashin()
    {
        $where = "salesmen_cashin.created_at_date between '2019-08-28'  AND '2019-08-29' ";
        $query = \DB::table('salesmen_cashin')
            ->join('salesmen', 'salesmen_cashin.salesman_id', '=', 'salesmen.id')
            ->join('branches', 'salesmen.branch_id', '=', 'branches.id')
            ->join('clients', 'salesmen_cashin.client_id', '=', 'clients.id')
            ->leftJoin('pos_billetera', 'salesmen_cashin.destination', '=', 'pos_billetera.msisdn')
            ->select(\DB::raw("salesmen_cashin.id, salesmen_cashin.client_id, salesmen_cashin.destination, COALESCE (pos_billetera.category, 'Linea Editada') as category, 
        salesmen_cashin.transaction_id, salesmen_cashin.status, salesmen_cashin.created_at as created_at, 
        salesmen_cashin.amount, salesmen.name as salesman, branches.description as branch, clients.description as client, clients.pos_code,
        TO_CHAR(salesmen_cashin.created_at, 'HH24:MI:SS') AS hour"))
            ->groupBy(\DB::raw("salesmen_cashin.id, salesmen_cashin.destination, pos_billetera.category, 
        salesmen_cashin.client_id, salesmen_cashin.transaction_id, salesmen_cashin.status, salesmen_cashin.created_at_date,
        TO_CHAR(salesmen_cashin.created_at, 'HH24:MI:SS'), salesmen.name, clients.pos_code, branches.description,
        clients.description"))
            ->whereRaw($where)
            ->orderBy('salesmen_cashin.id', 'desc')
            ->get();

        $this->sendCsv($query, ' # ; Cliente ID ; Destino ; Categoria ; ID Transaccion ; Estado ; Fecha de creacion; Monto; Vendedor; Sucursal; Cliente; POS_CODE; Hora
                ', 'Reporte');
    }

    public function credit_transactions()
    {
        $clients_cashins = \DB::table('clients_cashins')
            ->join('salesmen', 'clients_cashins.salesman_id', '=', 'salesmen.id')
            ->join('clients', 'clients_cashins.client_id', '=', 'clients.id')
            ->join('pos_billetera', 'clients_cashins.billetera_id', '=', 'pos_billetera.id')
            ->join('circuits', 'pos_billetera.circuit_id', '=', 'circuits.id')
            ->join('branches', 'salesmen.branch_id', '=', 'branches.id')
            ->selectRaw("clients_cashins.id, clients_cashins.id_momo, 'Tigo Money'::text AS product, 
            clients_cashins.factura_pago::integer AS payment_id, clients_cashins.msisdn AS destination, 
            clients_cashins.amount, clients_cashins.transfer, clients_cashins.transaction_id::text AS transaction_id, 
            clients.pos_code, clients.description as client, circuits.description AS circuits, salesmen.name AS salesmen,
            branches.description AS branch, clients_cashins.created_at, clients_cashins.bank_transaction,
            clients_cashins.payment_transaction, clients_cashins.type, clients_cashins.tipo_movimiento,
            clients_cashins.product_description, clients_cashins.receipt, clients_cashins.status,
            to_char(clients_cashins.created_at, 'DD/MM/YYYY'::text) AS date, to_char(clients_cashins.created_at, 'HH24:MI'::text) AS hour,
            clients_cashins.salesman_id, salesmen.branch_id, date(clients_cashins.created_at) AS created_at_date,
            1 AS ptype")
            ->whereRaw("clients_cashins.created_at BETWEEN '2019-08-20 00:00:00' and '2019-08-29 23:59.59'");


        $pos_credit = \DB::table('pos_bank_credit')
            ->join('salesmen', 'pos_bank_credit.salesman_id', '=', 'salesmen.id')
            ->join('clients', 'pos_bank_credit.client_id', '=', 'clients.id')
            ->join('pos', 'pos_bank_credit.pos_id', '=', 'pos.id')
            ->join('circuits', 'pos.circuit_id', '=', 'circuits.id')
            ->join('branches', 'salesmen.branch_id', '=', 'branches.id')
            ->selectRaw("pos_bank_credit.id, pos_bank_credit.id_momo, 'Epin'::text AS product, 
            pos_bank_credit.invoice_id AS payment_id, pos_bank_credit.msisdn AS destination, pos_bank_credit.amount, 
            pos_bank_credit.transfer, pos_bank_credit.transaction_id::text AS transaction_id, clients.pos_code,
            clients.description AS client, circuits.description AS circuits, salesmen.name AS salesmen,
            branches.description AS branch, pos_bank_credit.created_at, pos_bank_credit.bank_transaction,
            pos_bank_credit.payment_transaction, pos_bank_credit.type, pos_bank_credit.tipo_movimiento,
            pos_bank_credit.product_description, pos_bank_credit.receipt, pos_bank_credit.status,
            to_char(pos_bank_credit.created_at, 'DD/MM/YYYY'::text) AS date, to_char(pos_bank_credit.created_at, 'HH24:MI'::text) AS hour,
            pos_bank_credit.salesman_id, salesmen.branch_id, pos_bank_credit.created_at_date, 2 AS ptype")
            ->whereRaw("pos_bank_credit.created_at BETWEEN '2019-08-20 00:00:00' and '2019-08-29 23:59.59'")
            ->union($clients_cashins);



        $tcc_credit = \DB::table('tcc_bank_credits')
            ->join('salesmen', 'tcc_bank_credits.salesman_id', '=', 'salesmen.id')
            ->join('clients', 'tcc_bank_credits.client_id', '=', 'clients.id')
            ->join('pos_billetera', 'tcc_bank_credits.pos_id', '=', 'pos_billetera.id')
            ->join('circuits', 'pos_billetera.circuit_id', '=', 'circuits.id')
            ->join('branches', 'salesmen.branch_id', '=', 'branches.id')
            ->selectRaw("tcc_bank_credits.id, tcc_bank_credits.id_momo,
            'TCC'::text AS product, tcc_bank_credits.invoice_id AS payment_id,
            tcc_bank_credits.msisdn AS destination, tcc_bank_credits.amount, tcc_bank_credits.transfer,
            tcc_bank_credits.transaction_id::text AS transaction_id, clients.pos_code, clients.description AS client,
            circuits.description AS circuits, salesmen.name AS salesmen, branches.description AS branch,
            tcc_bank_credits.created_at, tcc_bank_credits.bank_transaction, tcc_bank_credits.payment_transaction,
            tcc_bank_credits.type, tcc_bank_credits.tipo_movimiento, tcc_bank_credits.product_description,
            tcc_bank_credits.receipt, tcc_bank_credits.status, to_char(tcc_bank_credits.created_at, 'DD/MM/YYYY'::text) AS date,
            to_char(tcc_bank_credits.created_at, 'HH24:MI'::text) AS hour, tcc_bank_credits.salesman_id,
            salesmen.branch_id, tcc_bank_credits.created_at_date, 3 AS ptype")
            ->whereRaw("tcc_bank_credits.created_at BETWEEN '2019-08-20 00:00:00' and '2019-08-29 23:59.59'")
            ->union($pos_credit)
            ->get();


        dd($tcc_credit);


    }

    private function sendCsv($sqlquery,$header,$filename)
    {
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"$filename-" . time() . ".csv\"");
        $data = "Reporte\n";
        $data .= "$header\n";

        foreach ($sqlquery as $row) {
            foreach ($row as $field) {
                $data .= "$field;";
            }
            $data .= "\n";
        }
        echo $data;
        die();
    }
}
