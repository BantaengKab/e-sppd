<?php

namespace App\Services;

use App\Models\SPPD;
use Dompdf\Dompdf;
use Dompdf\Options;

class SPPDPdfService
{
    protected Dompdf $dompdf;

    public function __construct()
    {
        $this->dompdf = new Dompdf();
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $this->dompdf->setOptions($options);
    }

    /**
     * Generate SPPD PDF
     */
    public function generateSPPD(SPPD $sppd): string
    {
        $spt = $sppd->spt;
        $user = $spt->user;
        $estimatedCosts = $spt->estimatedCosts;

        $html = $this->generateSPPDHtml([
            'sppd' => $sppd,
            'spt' => $spt,
            'user' => $user,
            'estimatedCosts' => $estimatedCosts,
        ]);

        $this->dompdf->loadHtml($html);
        $this->dompdf->setPaper('A4', 'portrait');
        $this->dompdf->render();

        return $this->dompdf->output();
    }

    /**
     * Generate SPPD HTML template
     */
    private function generateSPPDHtml(array $data): string
    {
        extract($data);

        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <title>SPPD - {$sppd->number}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 40px; }
                .header { text-align: center; margin-bottom: 30px; }
                .title { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
                .subtitle { font-size: 14px; margin-bottom: 20px; }
                .info-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .info-table td { padding: 5px; border: 1px solid #000; }
                .info-table .label { font-weight: bold; width: 30%; }
                .cost-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                .cost-table th, .cost-table td { padding: 5px; border: 1px solid #000; text-align: left; }
                .cost-table th { background-color: #f0f0f0; }
                .cost-table .amount { text-align: right; }
                .signature { margin-top: 50px; }
                .signature-table { width: 100%; border-collapse: collapse; }
                .signature-table td { padding: 5px; text-align: center; }
                .signature-line { border-bottom: 1px solid #000; height: 30px; }
            </style>
        </head>
        <body>
            <div class='header'>
                <div class='title'>PEMERINTAH KABUPATEN BANTAENG</div>
                <div class='subtitle'>SURAT PERINTAH PERJALANAN DINAS (SPPD)</div>
                <div class='subtitle'>Nomor: {$sppd->number}</div>
            </div>

            <table class='info-table'>
                <tr>
                    <td class='label'>Nama / NIP</td>
                    <td>: {$user->name} / {$user->nip}</td>
                </tr>
                <tr>
                    <td class='label'>Jabatan</td>
                    <td>: {$user->jabatan}</td>
                </tr>
                <tr>
                    <td class='label'>Unit Kerja</td>
                    <td>: {$user->unit_kerja}</td>
                </tr>
                <tr>
                    <td class='label'>Maksud Perjalanan Dinas</td>
                    <td>: {$spt->purpose}</td>
                </tr>
                <tr>
                    <td class='label'>Tempat Tujuan</td>
                    <td>: {$spt->destination}</td>
                </tr>
                <tr>
                    <td class='label'>Lama Perjalanan</td>
                    <td>: {$spt->start_date->format('d F Y')} s.d. {$spt->end_date->format('d F Y')}</td>
                </tr>
                <tr>
                    <td class='label'>Tanggal Keluar</td>
                    <td>: " . ($sppd->issue_date ? $sppd->issue_date->format('d F Y') : '-') . "</td>
                </tr>
                <tr>
                    <td class='label'>Kendaraan</td>
                    <td>: Dinas</td>
                </tr>
            </table>

            <h3>Rincian Biaya:</h3>
            <table class='cost-table'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Uraian</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>";

        $total = 0;
        $row = 1;
        foreach ($estimatedCosts as $cost) {
            $html .= "
                    <tr>
                        <td>{$row}</td>
                        <td>" . ucfirst($cost->type) . ($cost->description ? ' - ' . $cost->description : '') . "</td>
                        <td class='amount'>Rp " . number_format($cost->amount, 2, ',', '.') . "</td>
                    </tr>";
            $total += $cost->amount;
            $row++;
        }

        $html .= "
                    <tr>
                        <td colspan='2'><strong>Jumlah Total</strong></td>
                        <td class='amount'><strong>Rp " . number_format($total, 2, ',', '.') . "</strong></td>
                    </tr>
                </tbody>
            </table>

            <div class='signature'>
                <table class='signature-table'>
                    <tr>
                        <td width='33%'>
                            <div>Mengetahui,</div>
                            <div>Atasan Langsung</div>
                            <div class='signature-line'></div>
                            <div><small>(NIP)</small></div>
                        </td>
                        <td width='33%'>
                            <div>Pelaksana Tugas,</div>
                            <div class='signature-line'></div>
                            <div>{$user->name}</div>
                            <div><small>(NIP. {$user->nip})</small></div>
                        </td>
                        <td width='33%'>
                            <div>Bantaeng, " . date('d F Y') . "</div>
                            <div>Pengguna Anggaran</div>
                            <div class='signature-line'></div>
                            <div><small>(NIP)</small></div>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>";
    }
}