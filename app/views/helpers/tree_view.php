<?php
class TreeViewHelper extends Helper {

    function createListTree($tree) {
        $out = '';

        $depth = 0;
        $prev_depth = 0;
        $count = 0;
        foreach ($tree as $id => $node) {
            $depth = strrpos($node, '_');
            if ($depth === false) {
                $depth = 0;
                $clean_node = $node;
            } else {
                $depth = $depth + 1;
                $clean_node = substr($node, strrpos($node, '_')+1);
            }

            if ($depth > $prev_depth) {
                $out .= "\n<ul>\n";
            } else if ($depth < $prev_depth) {
                for ($i = 0; $i < ($prev_depth-$depth); $i++) {
                    $out .= "</li></ul>\n";
                }
            } else if ($count>0) {
                $out .= "\n</li>\n";
            }

            $out .= '<li id="node_' . $id . '" noDelete="true" noRename="true"><a class="drag" href="/positions/view/' . $id . '/' . $id . '/">' . $clean_node . '</a>';
            $out .= "\n";

            $prev_depth = $depth;
            $count++;
        }
        for ($i = 0; $i < ($depth); $i++) {
            $out .= "</li></ul>\n";
        }
        if (!empty($tree)) {
            $out .= '</li>';
        }

        return $out;
    }
}


?>
