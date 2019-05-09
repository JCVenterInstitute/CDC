<?php

/* preview_sql.twig */
class __TwigTemplate_b5e578ed3a5c8dbf1510eb38ac200f884869fa75d49c3defa0a3ca25caf8c463 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div class=\"preview_sql\">
    ";
        // line 2
        if (twig_test_empty((isset($context["query_data"]) ? $context["query_data"] : null))) {
            // line 3
            echo "        ";
            echo _gettext("No change");
            // line 4
            echo "    ";
        } elseif (twig_test_iterable((isset($context["query_data"]) ? $context["query_data"] : null))) {
            // line 5
            echo "        ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["query_data"]) ? $context["query_data"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["query"]) {
                // line 6
                echo "            ";
                echo PhpMyAdmin\Util::formatSql($context["query"]);
                echo "
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['query'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 8
            echo "    ";
        } else {
            // line 9
            echo "        ";
            echo PhpMyAdmin\Util::formatSql((isset($context["query_data"]) ? $context["query_data"] : null));
            echo "
    ";
        }
        // line 11
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "preview_sql.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 11,  47 => 9,  44 => 8,  35 => 6,  30 => 5,  27 => 4,  24 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "preview_sql.twig", "/local/ifs2_projdata/8500/projects/CDC/server/apache/htdocs/phpMyAdmin-4.8.1-english/templates/preview_sql.twig");
    }
}
