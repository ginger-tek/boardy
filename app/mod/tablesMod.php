<?php

namespace App\Mod;

class TablesMod
{
  private static function cap($s)
  {
    return join(' ', array_map(fn ($w) => strtoupper($w[0]) . substr($w, 1), preg_split('/(?=[A-Z])|[_-]/', $s)));
  }

  static function render(array $data, ?array $fields = null): string
  {

    $cols = $fields ?? (count($data) > 0 ? array_keys(get_object_vars($data[0])) : []);
    $cols = array_map(function ($c) {
      if (is_array($c)) {
        if (!@$c['label']) $c['label'] = TablesMod::cap($c['name']);
        return (object)$c;
      }
      return (object)['name' => $c, 'label' => TablesMod::cap($c)];
    }, $cols);
    if (count($data) == 0) $rows = '<tr><td colspan="' . count($cols) . '" align="center">No data to show</td></tr>';
    else $rows = join("\n", array_map(fn ($r) => '<tr>' . join("\n", array_map(fn ($c) => '<td>' . (@$c->format ? ($c->format)($r) : $r->{$c->name}) . '</td>', $cols)) . '</tr>', $data));
    return '<table>
      <thead>
        <tr>' . join("\n", array_map(fn ($c) => "<th>$c->label</th>", $cols)) . '</tr>
      </thead>
      <tbody>' . $rows . '</tbody>
      </table>';
  }
}
