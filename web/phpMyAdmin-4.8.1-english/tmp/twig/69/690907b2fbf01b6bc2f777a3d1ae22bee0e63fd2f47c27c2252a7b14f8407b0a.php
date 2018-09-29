<?php

/* table/browse_foreigners/column_element.twig */
class __TwigTemplate_edb24b6feac231379de4ab4fa2ded31de79bcda6925343d69b960603041cb7d7 extends Twig_Template
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
        echo "<td";
        echo (((isset($context["nowrap"]) ? $context["nowrap"] : null)) ? (" class=\"nowrap\"") : (""));
        echo ">
    ";
        // line 2
        echo (((isset($context["is_selected"]) ? $context["is_selected"] : null)) ? ("<strong>") : (""));
        echo "
        <a class=\"foreign_value\" data-key=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["keyname"]) ? $context["keyname"] : null), "html", null, true);
        echo "\" href=\"#\" title=\"";
        // line 4
        echo _gettext("Use this value");
        echo twig_escape_filter($this->env, (( !twig_test_empty((isset($context["title"]) ? $context["title"] : null))) ? ((": " . (isset($context["title"]) ? $context["title"] : null))) : ("")), "html", null, true);
        echo "\">
            ";
        // line 5
        if ((isset($context["nowrap"]) ? $context["nowrap"] : null)) {
            // line 6
            echo "                ";
            echo twig_escape_filter($this->env, (isset($context["keyname"]) ? $context["keyname"] : null), "html", null, true);
            echo "
            ";
        } else {
            // line 8
            echo "                ";
            echo twig_escape_filter($this->env, (isset($context["description"]) ? $context["description"] : null), "html", null, true);
            echo "
            ";
        }
        // line 10
        echo "        </a>
    ";
        // line 11
        echo (((isset($context["is_selected"]) ? $context["is_selected"] : null)) ? ("</strong>") : (""));
        echo "
</td>
";
    }

    public function getTemplateName()
    {
        return "table/browse_foreigners/column_element.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  53 => 11,  50 => 10,  44 => 8,  38 => 6,  36 => 5,  31 => 4,  28 => 3,  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "table/browse_foreigners/column_element.twig", "/local/ifs2_projdata/8500/projects/CDC/server/apache/htdocs/phpMyAdmin-4.8.1-english/templates/table/browse_foreigners/column_element.twig");
    }
}
