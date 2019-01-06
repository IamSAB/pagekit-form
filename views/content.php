<?php
    use Pagekit\Routing\Generator\UrlGenerator;
?>

<h1 style="margin: 0 0 30px 0; padding-bottom: 25px; border-bottom: 1px solid #e5e5e5; font-size: 34px; line-height: 40px; font-family: Helvetica, Arial, sans-serif; font-weight: normal; color: #333 !important;">
    <?= isset($title) ? $title : $subject ?>
</h1>

<?php if (isset($desc)): ?>
    <p style="padding: 30px; font-size: 16px; line-height: 20px; font-family: Helvetica, Arial, sans-serif; color: #999;">
        <?= $desc ?>
    </p>
<?php endif ?>

<table id="values-table" width="100%" style="border-collapse: collapse; margin: 20px 0; text-align: left;">
    <?php foreach ($values as $key => $value): ?>
        <tr <?= array_search($key, array_keys($values))%2 ? 'style="background-color: #f2f2f2;"': ''; ?>>
            <td style="padding: 10px 10px;"><?= ucfirst($key) ?></td>
            <td style="padding: 10px 10px;">
                <?php if (is_array($value)): ?>
                    <?php foreach ($value as $i => $val): ?>
                        <?= $i > 0 ? '<br>' : '' ?> <?= $val ?>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= $value ?>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<p style="margin: 50px 0 0 0; padding-top: 30px; border-top: 1px solid #e5e5e5; font-size: 14px; line-height: 20px; font-family: Helvetica, Arial, sans-serif; color: #999;">
    <a style="color: #777; text-decoration: none;" href="<?= $app['url']->base(0) ?>">
        <?= $app->config('system/site')->get('title')?>
    </a>
     >
    <a style="color: #777; text-decoration: none;" href="<?= $node->getUrl(UrlGenerator::ABSOLUTE_URL) ?>">
        <?= $node->title ?>
    </a>
    <br>
    <small>
        <?= (new DateTime())->format('Y-m-d H:i') ?>
    </small>
</p>