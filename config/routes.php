<?php


Flight::route('GET /',          fn() => (new DashboardController())->index());
Flight::route('GET /dashboard', fn() => (new DashboardController())->index());
Flight::route('GET /',        fn() => (new AccueilController())->index());
Flight::route('GET /accueil', fn() => (new AccueilController())->index());

Flight::route('GET /besoin',              fn()      => (new BesoinController())->index());
Flight::route('GET /besoin/create',       fn()      => (new BesoinController())->create());
Flight::route('POST /besoin/store',       fn()      => (new BesoinController())->store());
Flight::route('GET /besoin/edit/@id',     fn($id)   => (new BesoinController())->edit($id));
Flight::route('POST /besoin/update/@id',  fn($id)   => (new BesoinController())->update($id));
Flight::route('GET /besoin/delete/@id',   fn($id)   => (new BesoinController())->delete($id));


Flight::route('GET /don',                fn()      => (new DonController())->index());
Flight::route('GET /don/create',         fn()      => (new DonController())->create());
Flight::route('POST /don/store',         fn()      => (new DonController())->store());
Flight::route('GET /don/edit/@id',       fn($id)   => (new DonController())->edit($id));
Flight::route('POST /don/update/@id',    fn($id)   => (new DonController())->update($id));
Flight::route('GET /don/delete/@id',     fn($id)   => (new DonController())->delete($id));

Flight::route('GET /attribution',              fn()    => (new AttributionController())->index());
Flight::route('GET /attribution/create',       fn()    => (new AttributionController())->create());
Flight::route('POST /attribution/store',       fn()    => (new AttributionController())->store());
Flight::route('GET /attribution/delete/@id',   fn($id) => (new AttributionController())->delete($id));

Flight::route('GET /ville',              fn()    => (new VilleController())->index());
Flight::route('GET /ville/create',       fn()    => (new VilleController())->create());
Flight::route('POST /ville/store',       fn()    => (new VilleController())->store());
Flight::route('GET /ville/edit/@id',     fn($id) => (new VilleController())->edit($id));
Flight::route('POST /ville/update/@id',  fn($id) => (new VilleController())->update($id));
Flight::route('GET /ville/delete/@id',   fn($id) => (new VilleController())->delete($id));

Flight::route('GET /achat',              fn()    => (new AchatController())->index());
Flight::route('GET /achat/create',       fn()    => (new AchatController())->create());
Flight::route('POST /achat/store',       fn()    => (new AchatController())->store());
Flight::route('GET /achat/delete/@id',   fn($id) => (new AchatController())->delete($id));

Flight::route('GET /vente',              fn()    => (new VenteController())->index());
Flight::route('GET /vente/create',       fn()    => (new VenteController())->create());
Flight::route('POST /vente/store',       fn()    => (new VenteController())->store());
Flight::route('GET /vente/delete/@id',   fn($id) => (new VenteController())->delete($id));

Flight::route('GET /reinitialiser', fn() => (new ReinitController())->index());
Flight::route('POST /reinitialiser/confirmer', fn() => (new ReinitController())->confirmer());

Flight::route('GET /recap',       fn() => (new RecapController())->index());
Flight::route('GET /recap/data',  fn() => (new RecapController())->data());
