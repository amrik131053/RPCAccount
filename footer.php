 <footer class="footer footer-transparent d-print-none" style="background-color:#223260;color:white;"> 
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item"><a href="Dashboard" target="_blank" class="link-secondary"
                                        rel="noopener" style="color:white;">Design & Developed By </a></li>
                                <li class="list-inline-item" style="color:white;"><a href="Dashboard" class="link-secondary" style="color:white;"> Information
                                        Technology</a></li>

                                <li class="list-inline-item">

                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">


                                </li>
                                <li class="list-inline-item"  >
                                    <a href="Dashboard" class="link-secondary" rel="noopener" style="color:white;">
                                        Technical Helpline 91-78146-79220
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="styles/dist/js/tabler.min.js?1684106062" defer></script>
     <script src="script.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">

  function enableTableSorting(table) {
  if (!table) return;

  const headers = table.querySelectorAll('thead th');
  let sortDirections = Array(headers.length).fill(true);

  headers.forEach((header, index) => {
    header.style.cursor = 'pointer';
    header.addEventListener('click', () => {
      sortTableByColumn(table, index, sortDirections[index]);
      sortDirections[index] = !sortDirections[index];
    });
  });

  function sortTableByColumn(table, columnIndex, asc = true) {
    const dirModifier = asc ? 1 : -1;
    const tBody = table.tBodies[0];
    const rows = Array.from(tBody.querySelectorAll('tr'));

    const sortedRows = rows.sort((a, b) => {
      const aText = a.children[columnIndex].innerText.trim();
      const bText = b.children[columnIndex].innerText.trim();

      const aNum = parseFloat(aText.replace(/[^0-9.-]+/g, ''));
      const bNum = parseFloat(bText.replace(/[^0-9.-]+/g, ''));

      if (!isNaN(aNum) && !isNaN(bNum)) {
        return (aNum - bNum) * dirModifier;
      } else {
        return aText.localeCompare(bText) * dirModifier;
      }
    });

    tBody.innerHTML = '';
    tBody.append(...sortedRows);
  }
}



</script>
</body>

</html>