<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h2>Liste des comptes</h2>
            <a href="index.php?action=create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau compte
            </a>
        </div>
        
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <form action="index.php" method="GET" class="d-flex">
                        <input type="hidden" name="action" value="index">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher un compte..." value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="btn btn-outline-secondary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="index.php" method="GET" id="typeForm">
                        <input type="hidden" name="action" value="index">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <select name="type" class="form-select" onchange="document.getElementById('typeForm').submit()">
                            <option value="">Type de compte</option>
                            <?php foreach ($types as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= $type === $t ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
                <div class="col-md-3">
                    <form action="index.php" method="GET" id="orderForm">
                        <input type="hidden" name="action" value="index">
                        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
                        <select name="orderBy" class="form-select" onchange="document.getElementById('orderForm').submit()">
                            <option value="numero" <?= $orderBy === 'numero' ? 'selected' : '' ?>>Trier par numéro</option>
                            <option value="titulaire" <?= $orderBy === 'titulaire' ? 'selected' : '' ?>>Trier par titulaire</option>
                            <option value="solde" <?= $orderBy === 'solde' ? 'selected' : '' ?>>Trier par solde</option>
                            <option value="date_creation" <?= $orderBy === 'date_creation' ? 'selected' : '' ?>>Trier par date</option>
                        </select>
                    </form>
                </div>
            </div>
            
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    $success = $_GET['success'];
                    if ($success === 'compte_created') echo "Le compte a été créé avec succès.";
                    elseif ($success === 'compte_updated') echo "Le compte a été mis à jour avec succès.";
                    elseif ($success === 'compte_deleted') echo "Le compte a été supprimé avec succès.";
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    $error = $_GET['error'];
                    if ($error === 'compte_not_found') echo "Le compte demandé n'existe pas.";
                    elseif ($error === 'delete_failed') echo "Erreur lors de la suppression du compte.";
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>N° Compte</th>
                            <th>Titulaire</th>
                            <th>Type</th>
                            <th>Solde</th>
                            <th>Date de création</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($comptes)): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun compte trouvé</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($comptes as $compte): ?>
                                <tr>
                                    <td><?= htmlspecialchars($compte->getNumero()) ?></td>
                                    <td><?= htmlspecialchars($compte->getTitulaire()) ?></td>
                                    <td>
                                        <span class="badge <?= $compte->getType() === 'Épargne' ? 'bg-info' : 'bg-secondary' ?>">
                                            <?= htmlspecialchars($compte->getType()) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($compte->formaterSolde()) ?></td>
                                    <td><?= htmlspecialchars($compte->formaterDate()) ?></td>
                                    <td>
                                        <span class="badge <?= $compte->getStatut() === 'Actif' ? 'bg-success' : 'bg-warning' ?>">
                                            <?= htmlspecialchars($compte->getStatut()) ?>
                                            <?php if ($compte->getStatut() === 'Bloqué'): ?>
                                                (30j)
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="index.php?action=view&id=<?= $compte->getId() ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?action=edit&id=<?= $compte->getId() ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="index.php?action=delete&id=<?= $compte->getId() ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if ($totalPages > 1): ?>
                <nav aria-label="Pagination">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?action=index&page=<?= $page-1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>&orderBy=<?= urlencode($orderBy) ?>">
                                Précédent
                            </a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="index.php?action=index&page=<?= $i ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>&orderBy=<?= urlencode($orderBy) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link" href="index.php?action=index&page=<?= $page+1 ?>&search=<?= urlencode($search) ?>&type=<?= urlencode($type) ?>&orderBy=<?= urlencode($orderBy) ?>">
                                Suivant
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>