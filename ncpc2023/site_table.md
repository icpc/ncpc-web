<table class="site-table">
  <tr>
    <th style="text-align: left;"></th>
    <th style="text-align: left;">University</th>
    <th style="text-align: left;">Site Director(s)</th>
    <th style="text-align: left;">Status</th>
  </tr>
  {% for country in site.data.ncpc2023sites %}
    {% for uni in country.unis %}
      <tr>
        <td><span title="{{ country.name }}">{{ country.emoji }}</span></td>
        <td >
          {% if uni.url %} <a href="{{ uni.url }}"> {% endif %}
            <div>
            {{ uni.name }}
            </div>
            {% for extra_uni in uni.extra_unis %}
              <div> {{ extra_uni.name }} </div>
            {% endfor %}
          {% if uni.url %} </a> {% endif %}
          </td>
        <td style="font-size:12;">
          <div>
          <span>{{ uni.contact }}</span>
          <code>&lt;{{ uni.email }}&gt;</code>
          </div>
          {% for extra_contact in uni.extra_contacts %}
          <div>
            <span>{{ extra_contact.name }}</span>
            <code>&lt;{{ extra_contact.email }}&gt;</code>
          </div>
          {% endfor %}
        </td>
        <td>{% if uni.confirmed %} ✅ Confirmed {% else %} Pending Confirmation {% endif %} </td>
      </tr>
    {% endfor %}
  {% endfor %}
</table>