<!DOCTYPE html>
<html>

<head>
    <title>Recherche recette</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.1/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="~/../css/recette.css">
    <title>MarmIUTons - Recherche</title>
</head>

<body>
    <div class="wrapper">
        <?php include("~/../vue/header.php"); ?>
        <?php include("~/../vue/boot.php"); ?>

        <form action="index.php" method="get" class="recherchePage">
            <input type="hidden" name="action" value="recherche_recettes">
            <!--search bar-->
            <div class="search-bar">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <div>
                                <div class="input-group">
                                    <input type="search" placeholder="Rechercher une recette" name="terme" class="form-control searchControl">
                                    <div class="input-group-append">
                                        <!--Filtres-->
                                        <div class="btn-group" id="btn-grp-col-2">
                                            <select class="btn dropdown-toggle tag" name="sTag">
                                                <option> Catégorie </option>
                                                <?php
                                                foreach ($tagRecettes as $tag) {
                                                ?>
                                                    <option value="<?php echo $tag->getNomTag(); ?>"><?php echo $tag->getNomTag(); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <select class="btn dropdown-toggle" name="sIng">
                                                <option> Ingrédient </option>
                                                <?php
                                                foreach ($allIngredient as $ingredient) {
                                                ?>
                                                    <option value="<?php echo  $ingredient->getNomIngredient(); ?>"><?php echo  $ingredient->getNomIngredient(); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-link submit-button"><i class="fas fa-search magnify"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <div class="container justifiy-content-center mb-5">
            <div class="row row-cols-1 row-cols-md-4 g-4 d-flex justify-content-center" style="margin-top: 200px;">

                <?php
                if (empty($recettes_trouvees)) {
                    echo "<div><h1 style='font-weight:bolder; text-align:center; margin-top:150px;'>Aucun résultats ne corresponds à votre recherche.</h1></div>'";
                } else {
                    $j = 0;
                    foreach ($recettes_trouvees as $recette) {
                        $b = Recette::getImageBlob($recette->getPublicJoin('idPic'));
                        //On formate la date de publication
                        $date = new DateTime($recette->getDateCreation());
                        $date = $date->format('d-m-Y');

                ?>
                        <div class="col d-flex justify-content-center mt-6">
                            <div class="card" style="width: 18rem;">
                                <a class='ref' href='index.php?action=detailsRecette&numRecette=<?php echo $recette->getNumRecette() ?>'>

                                    <img class="card-img-top" src="data:image/jpg;base64,<?php echo base64_encode($b) ?>" style="height:200px" alt="Image recette">
                                    <div class="row diff diffi">
                                        <?php echo $recette->getPublicJoin('niveau') ?>
                                    </div>
                                    <?php
                                    // On affiche les option de modification pour les admins
                                    if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin") {
                                    ?>
                                        <div class="admin-overlay">
                                            <a href="index.php?action=deleteRecetteByNum&numRecette=<?php echo $recette->getNumRecette() ?>"><i class="fas fa-trash trash"></i></a>
                                            <a href="index.php?action=updateRecette&numRecette=<?php echo $recette->getNumRecette() ?>"><i class="fas fa-edit edit"></i></a>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                    <div class="card-body">

                                        <div class="note">
                                            <?php
                                            if (empty($recette->getNoteRecette())) {
                                                echo "N/N";
                                            } else {
                                                for ($i = 1; $i <= intval($recette->getNoteRecette()); $i++) {
                                                    echo "<i class='fas fa-star'></i>";
                                                }
                                                for ($i = 1; $i <= 5 - intval($recette->getNoteRecette()); $i++) {
                                                    echo "<i class='far fa-star'></i>";
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div class="row">
                                            <h2 class=' card-title nom-recette'><?php echo $recette->getNomRecette() ?></h2>
                                        </div>
                                        <div class="row row-cols-1 row-cols-md-3 d-flex justify-content-center">
                                            <span class='tempsR'>
                                                <i class='fa fa-clock'></i>
                                                <p><?php echo "{$recette->getTempsRecette()}mn" ?></p>
                                            </span>
                                            <span class='datePost'>
                                                <i class='far fa-calendar-alt'></i>
                                                <p><?php echo $date ?></p>
                                            </span>
                                            <span class='nbCommentaire'>
                                                <i class='fa fa-comments'></i>
                                                <p><?php
                                                    echo "{$nbCom[$j]['0']}";
                                                    $j++; ?></p>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
    <?php include("~/../vue/footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>