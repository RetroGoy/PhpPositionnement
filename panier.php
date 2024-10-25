<?php
session_start();

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'], $_POST['prix'], $_POST['quantite'])) {
    $produit = [
        "nom" => htmlspecialchars($_POST['nom']),
        "prix" => (float) $_POST['prix'],
        "quantite" => (int) $_POST['quantite']
    ];
    $_SESSION['panier'][] = $produit;
}

function calculerSousTotal($produit) {
    return $produit['prix'] * $produit['quantite'];
}

$totalPanier = 0;
foreach ($_SESSION['panier'] as $produit) {
    $totalPanier += calculerSousTotal($produit);
}

if ($totalPanier > 50) {
    $reduction = $totalPanier * 0.10;
    $totalPanierApresReduction = $totalPanier - $reduction;
} else {
    $totalPanierApresReduction = $totalPanier;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de Panier</title>
</head>
<body>

<h2>Ajouter un produit au panier</h2>
<form method="post" action="">
    <label for="nom">Nom du produit:</label>
    <input type="text" id="nom" name="nom" required><br><br>

    <label for="prix">Prix:</label>
    <input type="number" id="prix" name="prix" step="0.01" required><br><br>

    <label for="quantite">Quantité:</label>
    <input type="number" id="quantite" name="quantite" min="1" required><br><br>

    <input type="submit" value="Ajouter au panier">
</form>

<h2>Panier</h2>
<table border="1">
    <tr>
        <th>Nom</th>
        <th>Prix</th>
        <th>Quantité</th>
        <th>Sous-total</th>
    </tr>

    <?php foreach ($_SESSION['panier'] as $produit): ?>
        <tr>
            <td><?= $produit['nom'] ?></td>
            <td><?= $produit['prix'] ?> €</td>
            <td><?= $produit['quantite'] ?></td>
            <td><?= calculerSousTotal($produit) ?> €</td>
        </tr>
    <?php endforeach; ?>

    <tr>
        <td colspan="3">Total</td>
        <td><?= $totalPanier ?> €</td>
    </tr>

    <?php if ($totalPanier > 50): ?>
        <tr>
            <td colspan="3">Total après réduction</td>
            <td><?= $totalPanierApresReduction ?> €</td>
        </tr>
    <?php endif; ?>
</table>

</body>
</html>
