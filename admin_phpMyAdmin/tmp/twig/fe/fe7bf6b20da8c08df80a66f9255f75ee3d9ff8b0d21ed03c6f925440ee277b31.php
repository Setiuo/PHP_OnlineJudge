<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* table/relation/dropdown_generate.twig */
class __TwigTemplate_6bb2079a0c0b9e9c5a4e9a5e8561b1c3b8453e3d2f56ad318c3d0886c68eb610 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        (( !twig_test_empty(($context["dropdown_question"] ?? null))) ? (print (twig_escape_filter($this->env, ($context["dropdown_question"] ?? null), "html", null, true))) : (print ("")));
        // line 2
        echo "<select name=\"";
        echo twig_escape_filter($this->env, ($context["select_name"] ?? null), "html", null, true);
        echo "\">
";
        // line 3
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable(($context["choices"] ?? null));
        foreach ($context['_seq'] as $context["one_value"] => $context["one_label"]) {
            // line 4
            echo "    <option value=\"";
            echo twig_escape_filter($this->env, $context["one_value"], "html", null, true);
            echo "\"";
            // line 5
            if ((($context["selected_value"] ?? null) == $context["one_value"])) {
                echo " selected=\"selected\"";
            }
            echo ">
        ";
            // line 6
            echo twig_escape_filter($this->env, $context["one_label"], "html", null, true);
            echo "
    </option>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['one_value'], $context['one_label'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "</select>
";
    }

    public function getTemplateName()
    {
        return "table/relation/dropdown_generate.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 9,  51 => 6,  45 => 5,  41 => 4,  37 => 3,  32 => 2,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "table/relation/dropdown_generate.twig", "D:\\OpenJudge\\admin_phpMyAdmin\\templates\\table\\relation\\dropdown_generate.twig");
    }
}
