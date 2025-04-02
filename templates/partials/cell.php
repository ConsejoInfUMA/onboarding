<td>
    <?php if (!isset($name)): ?>
        <?=$this->e($value)?>
    <?php else: ?>
        <input name="<?=$name?>[<?=$i?>][<?=$key?>]" type="<?=$type ?? 'text'?>" value="<?=$this->e($value)?>" />
    <?php endif ?>
</td>
