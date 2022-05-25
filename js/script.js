const SUBDOMEN = "/work5";
var json = "";
let table_users = `<table><th></<th></table>`;
let table_orders = "";
let table_sql_request = "";
document.addEventListener("DOMContentLoaded", async function() {
  await loadUsers(1, "#users");
  await loadOrders(2, "#orders");

  $(".button--sql").on("click", async function() {
    $(".button").removeClass("active-button");
    $(this).addClass("active-button");
    let r = $(this).attr("sql_r");

    let columns = [
      {title:"Login", field:"login"},
      {title:"Email", field:"email"},
    ];
    await loadSqlReuest(r, columns);
  });
});

async function loadUsers(r, selector) {
  if ($(selector).lenght != 0) {
    const req = await fetch( window.location.protocol +
      "//" +
      window.location.hostname +
      ":" +
      window.location.port +
      SUBDOMEN + "/php/tables_r.php?r=" + r);
    let response = await req.json();

    let users = response[0];
    $(selector + " table .row").remove();
    users.forEach(el => {
      let tr = "<tr class='row'><td>"+el.id+"</td><td>"+el.login+"</td><td>"+el.email+"</td></tr>"
      $(selector + " table").append(tr);
    });
  }
}

async function loadOrders(r, selector) {
  if ($(selector).lenght != 0) {
    const req = await fetch( window.location.protocol +
      "//" +
      window.location.hostname +
      ":" +
      window.location.port +
      SUBDOMEN + "/php/tables_r.php?r=" + r);
    let response = await req.json();

    let orders = response[0];
    $(selector + " table .row").remove();
    orders.forEach(el => {
      let tr = "<tr class='row'><td>"+el.id+"</td><td>"+el.user_id+"</td><td>"+el.price+"</td></tr>"
      $(selector + " table").append(tr);
    });
  }
}

async function loadSqlReuest(r, columns, selector="#sql") {
  if ($(selector).lenght != 0) {
    const req = await fetch( window.location.protocol +
      "//" +
      window.location.hostname +
      ":" +
      window.location.port +
      SUBDOMEN + "/php/tables_r.php?r=" + r + "&v=1");
    let response = await req.json();

    let table = response[0];
    $(selector + " table .row").remove();
    table.forEach(el => {
      let field = "";
      if ( r == "3") {
        field = el.email;
      }

      if (r == "4" || r == "5") {
        field = el.login;
      }

      let tr = "<tr class='row'><td>"+field+"</td></tr>";
      $(selector + " table").append(tr);
    });

    $("#sql_request").text(response[1]);
  }
}
