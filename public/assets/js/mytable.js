class MyTable {
    constructor(tab,config = {}) {
        this.tab = tab;
        this.tabId = tab.substr(1);
        this.table = document.querySelector(tab);
        this.rows = this.table.querySelectorAll('tbody tr');
        if (config.sorting) this.sorting();
        if (config.searching || config.html) this.top(config);
        this.bottom(config);
        this.search_column = (config.search_column) ? config.search_column:[2,3];

        if (config.checkboxs) this.showChecked();
        this.action = (config.action)?config.action:false;
        this.refresh();

    }

     sorting(lang='en') {
    const getSort = ({ target }) => {
        const order = (target.dataset.order = -(target.dataset.order || -1));
        const index = [...target.parentNode.cells].indexOf(target);
        const collator = new Intl.Collator([lang, 'ru'], { numeric: true });
        const comparator = (index, order) => (a, b) => order * collator.compare(
            a.children[index].innerHTML,
            b.children[index].innerHTML
        );
        for(const tBody of target.closest('table').tBodies)
            tBody.append(...[...tBody.rows].sort(comparator(index, order)));
        for(const cell of target.parentNode.cells)
            cell.classList.toggle('sorted', cell === target);
    };
         this.table.querySelectorAll('thead th:not(.no-sort)').forEach(tableTH => tableTH.addEventListener('click', (ev) => getSort(ev)));

}

   selectAll(el) {
        let checkboxes = this.table.querySelectorAll('tbody .che');
        for (let i = 0; i < checkboxes.length; i++) { checkboxes[i].checked =(el.checked)?true:false;}
         this.showChecked()
    }

    showChecked(){
        let t = this.table.querySelectorAll('tbody input.che:checked').length;
        if(t==0) document.querySelector('.select-all').checked = false;
        //document.getElementById("del").disabled =  (t > 0)?false:true;
        if(document.getElementById("transfer")) document.getElementById("transfer").disabled =  (t > 0)?false:true;
    }


     searching(id) {
        let input = document.getElementById('input'+this.tabId);
        let filter = input.value.toUpperCase();
        let table = document.querySelector(id);
        let tr = table.getElementsByTagName("tr");
        let x = 0;
        let td1,td2,txtValue0,txtValue1,t;
        for (let i = 0; i < tr.length; i++) {
            td1 = tr[i].getElementsByTagName("td")[this.search_column[0]];
            td2 = tr[i].getElementsByTagName("td")[this.search_column[1]];
            if (td1 || td2) {
                txtValue0 = td1.textContent || td1.innerText;
                txtValue1 = td2.textContent || td2.innerText;
                if (txtValue0.toUpperCase().indexOf(filter) > -1) {
                    if(!td1.classList.contains('trasparent')) {
                        tr[i].style.display = "";
                        x++;
                    } else {
                        tr[i].style.display = "none";
                    }
                } else if (txtValue1.toUpperCase().indexOf(filter) > -1) {
                    if(!td2.classList.contains('trasparent')) {
                        tr[i].style.display = "";
                        x++;
                    } else {
                        tr[i].style.display = "none";
                    }
                } else {
                    tr[i].style.display = "none";
                }
                t = i;
            }
        }
        if(x > 0){
            document.getElementById(this.tabId+'total').innerHTML = '<small>Показано</small> '+x+' <small>из</small> '+t;
        } else {
            //document.getElementById(this.tabId).innerHTML = '<tbody><tr><td>ничего не найдено!</td></tbody></tr>  ';
            document.getElementById(this.tabId+'total').innerHTML = ' ничего не найдено! ';
        }
    }

    top(config){
        let search = '';
        let html = '';
         if(config.tophtml) html = config.tophtml;
        if(config.searching) search = '<div class="searchblock"><input type="text" id="input'+this.tabId+'" class="form-control" onkeyup="'+this.tabId+'.searching(\''+this.tab+'\')" placeholder="поиск слов..."></div>'
        this.table.insertAdjacentHTML("beforebegin", '<div class="mytable-top-panel">'+html+search+'</div>');
    }

    bottom(config){
        let html = '';
        if(config.bottomhtml) html = config.bottomhtml;
        this.table.insertAdjacentHTML("afterend", '<div class="mytable-bottom-panel"><div class="mytable-total" id="'+this.tabId+'total">0 из 0 слов</div>'+html+'</div>');
    }

    setTotal(){
        let rows = document.querySelectorAll(this.tab+' tbody tr');
        document.getElementById(this.tabId+'total').innerHTML = '<small>Показано</small> '+rows.length+' <small>из</small> '+rows.length;
    }

    total(){
        return document.querySelectorAll(this.tab+' tbody tr').length;
    }

    refresh(){
        if (this.action){
        let rows = document.querySelectorAll(this.tab+' tbody tr');
        rows.forEach(function (row) {
            row.querySelector('.delRow').addEventListener("click", function(e) { delRow(e.target) });
            row.querySelector('.editRow').addEventListener("click", function(e) { editRow(e.target)});
            row.style.display = '';
        });
        }
        this.setTotal();

    }
}