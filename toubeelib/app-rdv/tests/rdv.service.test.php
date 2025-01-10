<?php
require_once __DIR__ . '/../vendor/autoload.php';

$ServiceRDV = new \toubeelib\core\services\rdv\ServiceRDV(
    new \toubeelib\infrastructure\repositories\ArrayRdvRepository(),
    new \toubeelib\core\services\praticien\ServicePraticien(new \toubeelib\infrastructure\repositories\ArrayPraticienRepository()),
    new \Monolog\Logger('toubeelib', [new \Monolog\Handler\StreamHandler(__DIR__ . '/../logs/toubeelib.log', \Monolog\Logger::DEBUG)])
);

//créer un rdv à aprtir d'un InputRDVDTO
try {

    // cas 1 : praticien disponible
    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p1', // praticien_id
        'pa1', // patient_id
        'A', // specialite_id
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-10 09:00'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );
    $rdv1 = $ServiceRDV->creerRendezVous($inputRdv);
    print_r($rdv1);

    // cas 2 : praticien non disponible
    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p3', // praticien_id
        'pa1', // patient_id
        'C', // specialite_id
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-25 09:00'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );

    $rdv2 = $ServiceRDV->creerRendezVous($inputRdv);
    $rdv2 = $ServiceRDV -> nonHonorerRDV($rdv2->getId());
    print_r($rdv2);
    // $rdv3 = $ServiceRDV->creerRendezVous($inputRdv);    

    // cas 3 : praticien disponible, specialite qui ne correspond pas à celle du praticien
    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p3', // praticien_id
        'pa1', // patient_id
        'A', // specialite_id qui ne correspond pas à celle du praticien
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-11-15 09:00'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );
    $dispo = $ServiceRDV->isPraticienAvailable($inputRdv->praticien_id, $inputRdv->date);
    $check = $ServiceRDV->checkPraticienSpecialites($inputRdv->praticien_id, $inputRdv->specialite_id);
    // $rdv1 = $ServiceRDV->creerRendezVous($inputRdv);
    // print_r($rdv1);

    // cas 4 : annuler un rendez-vous
    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p1', // praticien_id
        'pa2', // patient_id
        'A', // specialite_id
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-11 09:00'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );

    $rdv4 = $ServiceRDV->creerRendezVous($inputRdv);
    echo "id du rdv créé : ".$rdv4->getId()."\n";
    $ServiceRDV->annulerRendezVous($rdv4->getId());
    $rdv4 = $ServiceRDV->getRendezVousById($rdv4->getId());         //on récupère le DTO du rdv annulé
    print_r($rdv4);
    echo "status du rdv : ".$rdv4->getStatus()."\n";
    

    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p1', // praticien_id
        'pa1', // patient_id
        'A', // specialite_id
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-11 09:30'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );

    $rdv5 = $ServiceRDV->creerRendezVous($inputRdv);

    // honorer le rendez-vous
    $rdv5 = $ServiceRDV->honorerRDV($rdv5->getId());
    print_r($rdv5);

    $inputRdv = new \toubeelib\core\dto\InputRDVDTO(
        'p1', // praticien_id
        'pa2', // patient_id
        'A', // specialite_id
        \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-11 11:00'), // date
        true, // newPatient
        false, // type
        false, // isConfirmed
        false, // isPaid
    );

    $rdv6 = $ServiceRDV->creerRendezVous($inputRdv);
    print_r($rdv6);

    // lister les rendez-vous d'un praticien à une date donnée
    $rdvs = $ServiceRDV->listerRendezVousPraticien('p1', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-11 09:00'), 2);
    foreach($rdvs as $rdv){
        print_r($rdv);
    }

    $dispos = $ServiceRDV->listerDisposPraticien('p1', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-11 09:00'), \DateTimeImmutable::createFromFormat('Y-m-d H:i','2027-10-13 16:00'));
    foreach($dispos as $dispo){
        print_r($dispo);
    }    

    //modifier le rendez vous
    $rdv6 = $ServiceRDV -> modifierRDV($rdv6->getId(), 'B', 'pa1');
    $rdv6 = $ServiceRDV -> payerRDV($rdv6->getId());  
    print_r($rdv6);
} catch (\toubeelib\core\services\rdv\ServiceRDVInvalidDataException $e) {
    echo $e->getMessage();
} catch (\toubeelib\core\services\praticien\ServicePraticienInvalidDataException $e) {
    echo $e->getMessage();
}catch(\Exception $e){
    echo $e->getMessage();
}
