<?php
    include('../app/classLoad.php');  
    include('../lib/image-processing.php');
    require_once('../lib/tcpdf/tcpdf.php');
    //classes loading end
    session_start();
    
    //classes managers
    $contratManager = new ContratManager(PDOFactory::getMysqlConnection());
    $companyManager = new CompanyManager(PDOFactory::getMysqlConnection());
    $clientManager  = new ClientManager(PDOFactory::getMysqlConnection());
    $projetManager  = new ProjetManager(PDOFactory::getMysqlConnection());
    //objects
    $idContrat = $_GET['idContrat'];
    $contrat   = $contratManager->getContratById($idContrat);
    $client    = $clientManager->getClientById($contrat->idClient());
    $projet    = $projetManager->getProjetById($contrat->idProjet());
    //vars
    $titreProjet  = $projet->titre();
    $numberProjet = str_replace(array('ANNAHDA', 'Annahda', 'annahda'), '', $projet->nom());
    $bien         = "";
    $typeBien     = "";
    $cave         = "";
    $etage        = "";
    $articleReservation = "";
    $articleRevente = "";
    if ( $contrat->typeBien() == "appartement" ) {
        $appartementManager = new AppartementManager(PDOFactory::getMysqlConnection());
        $bien = $appartementManager->getAppartementById($contrat->idBien());
        $typeBien = "شقة سكنية";
        if ( $bien->cave() == "Avec" ) {
            $cave = "بالمرأب";
        }
        else if ( $bien->cave() =="Sans" ) {
            $cave = "بدون مرأب";
        }
        $etage = $bien->niveau();        
        $articleReservation = "التي سبق لي أن حجزتها من اجل الاستفادة  في المشروع السكني المسمى : النهضة ".$numberProjet." التابع لملكية الشركة المذكورة أعلاه .";
        $articleRevente = "و اوافق للشركة المذكورة أعلاه أن تتصرف في الشقة التي سبق أن حفظت حقي في شرائها.";
    }
    else if ( $contrat->typeBien() == "localCommercial" ) {
        $locauxManager = new LocauxManager(PDOFactory::getMysqlConnection());
        $bien = $locauxManager->getLocauxById($contrat->idBien());
        $typeBien = "محل تجاري";
        $etage = "الطابق الأرضي";
        $articleReservation = "الذي سبق لي أن حجزته من اجل الاستفادة  في المشروع السكني المسمى : النهضة ".$numberProjet." التابع لملكية الشركة المذكورة أعلاه .";
        $articleRevente = "و اوافق للشركة المذكورة أعلاه أن تتصرف في المحل التجاري الذي سبق أن حفظت حقي في شرائه.";
    }
    $contratTitle = "شهادة اتمام ثمن ".$typeBien;
    $superficie = $bien->superficie();
    $numero = $bien->nom();
    $facade = $contrat->facadeArabe();
    $etatBien = "";
    if ( $contrat->etatBienArabe() == "GrosOeuvre" ) {
        $etatBien = "الأشغال الأساسية للبناء";    
    } 
    else if ( $contrat->etatBienArabe() == "Finition" ) {
        $etatBien = "الأشغال النهائية للبناء";
    }
    $contratReference = "";
    if ( strlen($contrat->reference()) > 1 ) {
        $contratReference = "رقم العقد : ".$contrat->reference().'-'.$contrat->id();   
    }
    $avanceLettresArabe = $contrat->avanceArabe();
    $prixLettresArabe = $contrat->prixVenteArabe();
    $idSociete = $contrat->societeArabe();
    $company = $companyManager->getCompanyById($idSociete);
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);
//$pdf->SetHeaderData(false, false, false, false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
//$pdf->setHeaderMargin(0);
// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';

// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);

// add a page
$pdf->AddPage();
// Restore RTL direction
$pdf->setRTL(true);
// set font
$pdf->SetFont('aealarabiya', '', 24);
// print newline
$pdf->Ln();
$pdf->Cell(0, 12, $contratTitle,0,1,'C');
$pdf->Ln();
$pdf->SetFont('aealarabiya', '', 16);
$pdf->Ln();
$pdf->Ln();
$htmlcontent = ' تشهد شركة : '.$company->nomArabe().'،  في شخص ممثلها القانوني, و الكائن مقرها الاجتماعي '.$company->adresseArabe().' بأن السيد(ة) '.$client->nomArabe().' قد أدى (ت) ما بذمته (ا) تجاه الشركة المذكورة أعلاه و المتمثل في القيمة المالية التي مجموعها '.number_format($contrat->prixVente(), 2, ',', '').' درهم (...............................................................................درهم)   ';
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();
$htmlcontent = 'المتعلقة بالاستفادة ب'.$typeBien.' بالمواصفات التالية : ';
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();
$pdf->Ln();
$htmlcontent = '&nbsp;&nbsp;*اسم المشروع : النهضة '.$numberProjet;
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$htmlcontent = '&nbsp;&nbsp;*رقم ال'.$typeBien.' : '.$bien->nom();
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
//Acte 2:
$htmlcontent = '&nbsp;&nbsp;*الطابق : '.$etage;
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$htmlcontent = "و بهذا نشهد و نقر";
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
//Contrat Footer:
$pdf->SetFont('aealarabiya', '', 16);
$htmlcontent = '<strong>'.'الناظور في '.'</strong>'.date('d/m/Y');
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Ln();
$htmlcontent = '<strong style="text-align:center">'.'التوقيع '.'</strong>';
$pdf->WriteHTML($htmlcontent, true, 0, true, 0);
$pdf->Ln();

//$pdf->Ln();
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('contrat.pdf', 'I');
// END OF FILE
//============================================================+