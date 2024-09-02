<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Helpers\Transaksi\TagihanHelper;
use App\Helpers\Transaksi\WordHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\TagihanRequest\CreateRequest;
use App\Http\Resources\Tagihan\TagihanCollection;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class TagihanController extends Controller
{
    private $transaksiTagihanHelper, $wordHelper;

    public function __construct(WordHelper $wordHelper)
    {
        $this->wordHelper = $wordHelper;
        $this->transaksiTagihanHelper = new TagihanHelper();
    }

    /**
     * @OA\Get(
     * path="/api/transaksi/laporan/invoice",
     * summary="Get data invoice",
     * tags={"Laporan Invoice"},
     * @OA\Response(
     *  response=200,
     *  description="Data invoice berhasil ditemukan"
     * ),
     * )
     */
    public function index(Request $request)
    {
        $result = $this->transaksiTagihanHelper->getDataPaginate($request->all());
        if ($result['status']) {
            # code...
            return response()->json([
                'status' => 'success',
                'data' => new TagihanCollection($result['data'])
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'],
            'dev' => $result['dev']
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/transaksi/laporan/invoice",
     * summary="Tambah data invoice",
     * tags = {"Laporan Invoice"},
     * @OA\RequestBody(
     *   required=true,
     *   description="Data yang dibutuhkan untuk menambah data invoice",
     *   @OA\JsonContent(
     *     required={"order_detail", "singkatan", "m_penyewa_id", "master_rekening_id"},
     *     
     *   )
     * ),
     * @OA\Response(
     *   response=200,
     *  description="Data invoice berhasil ditambahkan"
     *  ),
     * @OA\Response(
     *  response=400,
     * description="Data invoice gagal ditambahkan"
     * ),
     * @OA\Response(
     * response=422,
     * description="Data yang dibutuhkan tidak lengkap"
     * ),
     * )
     */
    public function create(CreateRequest $request){

        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                    'status' => 'error',
                    'message' => $request->validator->errors()
                ]
            );
        }

        $payload = $request->only([
            "singkatan",
            "order_detail",
            "m_penyewa_id",
            "master_rekening_id"
        ]);
        $result = $this->transaksiTagihanHelper->create($payload); 
        if ($result['status']) {
            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => $result['message'],
            'dev' => $result['dev']
        ]);
    }

    /**
     * @OA\Delete(
    *   path="/api/transaksi/laporan/invoice/{id}",
     *  summary="Hapus data invoice",
     * tags={"Laporan Invoice"},
     * @OA\Parameter(
     *  name="id",
     *  description="ID dari invoice/tagihan",
     *  required=true,
     *  in="path",
     * ),
     * @OA\Response(
     *  response=200,
     *  description="Data Invoice/Tagihan berhasil dihapus"
     * ),
     * )
     * )
     */
    public function destroy($id)
    {
        $result = $this->transaksiTagihanHelper->delete($id);
        if ($result['status']) {
            return response()->json([
                'status' => 'success',
                'message' => $result['message']
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => $result['message'],
            'dev' => $result['dev']
        ]);
    }


    public function generatePDF(string | int $id){
        $transaksi_tagihan = $this->transaksiTagihanHelper->getById($id);
        if ($transaksi_tagihan['status']) {
            Carbon::setLocale('id');
            $date_indo = Carbon::parse($transaksi_tagihan['data']['created_at'])->translatedFormat('j F Y');
            $data = ['data' => $transaksi_tagihan['data'], 'title' => 'Invoice Tagihan '  . 'No. ' . $transaksi_tagihan['data']['no_tagihan'], 'tanggal' => $date_indo];
            $pdf = Pdf::setPaper('A4','portrait')->loadView('generate.pdf.tagihan', $data);
            return $pdf->stream('Invoice Tagihan '  . 'No. ' . $transaksi_tagihan['data']['no_tagihan']. '.docx');
        }
        return [
            'status' => 'error',
            'message' => $transaksi_tagihan['message'],
            'dev'     => $transaksi_tagihan['dev']
        ];
    }

    public function generateWord(string | int $id){

        $transaksi_tagihan = $this->transaksiTagihanHelper->getById($id);

        if ($transaksi_tagihan['status']) {
            $result = $this->wordHelper->generateWord($transaksi_tagihan);

            // Mengirim file ke browser untuk diunduh
            return response()->download($result)->deleteFileAfterSend(true);
        }
        return [
            'status' => 'error',
            'message' => $transaksi_tagihan['message'],
            'dev'     => $transaksi_tagihan['dev']
        ];
    }
}
