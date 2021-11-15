<a href="?action=courses/list" class="btn btn-primary mb-3">
    Retour
</a>
<div class="card">
    <div class="card-body">
        <h5 class="card-title text-primary">Modifier une matière</h5>
        <form class="row mt-2" action="?action=courses/edit&id=<?= $courseEdited['id'] ?>" method="post">
            <input type="hidden" name="id" value="<?= $courseEdited['id'] ?>">
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="name" class="form-label">Nom</label>
                <input class="form-control" id="name" name="name" type="text" required
                       value="<?= $courseEdited['name'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="coefficient" class="form-label">Coefficient</label>
                <input class="form-control" id="coefficient" name="coefficient" type="number" min="1" step="1" required
                       value="<?= $courseEdited['coefficient'] ?>"/>
            </div>
            <div class="mb-2 col-md-12 col-lg-6">
                <label for="teacher" class="form-label">Professeur</label>
                <select name="teacher" id="teacher" class="form-control">
                    <option value="" disabled selected>Choisir un professeur</option>
                    <?php foreach ($all_teachers as $teacher) : ?>
                        <option value="<?= $teacher['id'] ?>" <?= $teacher['id'] === $courseEdited['teacher_id'] ? 'selected' : '' ?>>
                            <?= $teacher['email'] ?>
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