<a href="?action=promotions/list" class="btn btn-primary mb-3">
    Retour
</a>
<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">Ajouter une promotion</h5>
        <form class="row mt-2" action="?action=promotions/edit&id=<?= $promotionEdited['id'] ?>"  method="post">
            <input type="hidden" name="id" value="<?= $promotionEdited['id'] ?>">
            <div class="mb-2 col-md-12 col-lg-4">
                <label for="name" class="form-label">Nom</label>
                <input class="form-control" id="name" name="name" type="text" required
                       value="<?= $promotionEdited['promotion_name'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-4">
                <label for="start" class="form-label">Date de début</label>
                <input class="form-control" id="start" name="start" type="date" required
                       value="<?= $promotionEdited['promotion_start'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-4">
                <label for="end" class="form-label">Date de fin</label>
                <input class="form-control" id="end" name="end" type="date" required
                       value="<?= $promotionEdited['promotion_end'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="courses" class="form-label">Matières</label>
                <select name="courses[]" id="courses" class="form-control" multiple>
                    <option value="" disabled>Choisir des matières</option>
                    <?php foreach ($all_courses as $course) : ?>
                        <option value="<?= $course['id'] ?>" <?= in_array($course['id'], $promotionCourses) ? 'selected' : '' ?>>
                            <?= $course['name'] ?>
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