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

/* table/relation/internal_relational_row.twig */
class __TwigTemplate_c04830a168e5420618b66c12d205ea17b32c86558cbdfa5b1e550eb31d1dfcf7 extends \Twig\Template
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
        echo "<tr>
    <td class=\"vmiddle\">
        <strong>";
        // line 3
        echo twig_escape_filter($this->env, ($context["myfield"] ?? null), "html", null, true);
        echo "</strong>
        <input type=\"hidden\" name=\"fields_name[";
        // line 4
        echo twig_escape_filter($this->env, ($context["myfield_md5"] ?? null), "html", null, true);
        echo "]\"
               value=\"";
        // line 5
        echo twig_escape_filter($this->env, ($context["myfield"] ?? null), "html", null, true);
        echo "\"/>
    </td>

    <td>
        ";
        // line 9
        $this->loadTemplate("table/relation/relational_dropdown.twig", "table/relation/internal_relational_row.twig", 9)->display(twig_to_array(["name" => (("destination_db[" .         // line 10
($context["myfield_md5"] ?? null)) . "]"), "title" => _gettext("Database"), "values" =>         // line 12
($context["databases"] ?? null), "foreign" =>         // line 13
($context["foreign_db"] ?? null)]));
        // line 15
        echo "
        ";
        // line 16
        $this->loadTemplate("table/relation/relational_dropdown.twig", "table/relation/internal_relational_row.twig", 16)->display(twig_to_array(["name" => (("destination_table[" .         // line 17
($context["myfield_md5"] ?? null)) . "]"), "title" => _gettext("Table"), "values" =>         // line 19
($context["tables"] ?? null), "foreign" =>         // line 20
($context["foreign_table"] ?? null)]));
        // line 22
        echo "
        ";
        // line 23
        $this->loadTemplate("table/relation/relational_dropdown.twig", "table/relation/internal_relational_row.twig", 23)->display(twig_to_array(["name" => (("destination_column[" .         // line 24
($context["myfield_md5"] ?? null)) . "]"), "title" => _gettext("Column"), "values" =>         // line 26
($context["columns"] ?? null), "foreign" =>         // line 27
($context["foreign_column"] ?? null)]));
        // line 29
        echo "    </td>
</tr>
";
    }

    public function getTemplateName()
    {
        return "table/relation/internal_relational_row.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  70 => 29,  68 => 27,  67 => 26,  66 => 24,  65 => 23,  62 => 22,  60 => 20,  59 => 19,  58 => 17,  57 => 16,  54 => 15,  52 => 13,  51 => 12,  50 => 10,  49 => 9,  42 => 5,  38 => 4,  34 => 3,  30 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "table/relation/internal_relational_row.twig", "D:\\OpenJudge\\admin_phpMyAdmin\\templates\\table\\relation\\internal_relational_row.twig");
    }
}
