<a href="?action=teachers/list" class="btn btn-primary mb-3">
    Retour
</a>
<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">Modifier un professeur</h5>
        <form class="row mt-2" action="?action=teachers/edit&id=<?= $userEdited['id'] ?>" method="post">
            <input type="hidden" name="id" value="<?= $userEdited['id'] ?>">
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="lastname" class="form-label">Nom</label>
                <input class="form-control"
                       id="lastname"
                       name="lastname"
                       type="text"
                       required
                       value="<?= $userEdited['last_name'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="firstname" class="form-label">Prénom</label>
                <input value="<?= $userEdited['first_name'] ?>" class="form-control" id="firstname" name="firstname"
                       type="text" required/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="email" class="form-label">Email</label>
                <input value="<?= $userEdited['email'] ?>" class="form-control" id="email" name="email" type="email"
                       required/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="birthday" class="form-label">Date de naissance</label>
                <input value="<?= $userEdited['birthday'] ?>" class="form-control" id="birthday" name="birthday"
                       type="date" required/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="promotion" class="form-label">Promotion</label>
                <select name="promotion" id="promotion" class="form-control">
                    <option value="" disabled selected>Choisir une promotion</option>
                    <?php foreach ($all_promotions as $promotion) : ?>
                        <option value="<?= $promotion['id'] ?>" <?= $promotion['id'] === $userEdited['promotion_id'] ? 'selected' : '' ?>>
                            <?= $promotion['promotion_name'] ?>
                        </option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Modifier</button>
            </div>
        </form>
    </div>
</div>