function createTable(GPA_sort) {//用來製作GPA換算表格
        let table = document.createElement('table');
        table.setAttribute('align', 'center');
        table.setAttribute('border', '1');
            
        let colgroup = document.createElement('colgroup');//欄寬
        let col1 = document.createElement('col');
        col1.style.width = '200px';
        let col2 = document.createElement('col');
        col2.style.width = '200px';
        colgroup.appendChild(col1);
        colgroup.appendChild(col2);
        table.appendChild(colgroup);
            
        let headerRow = document.createElement('tr');//第一行
		headerRow.style.backgroundColor='rgb(120,120,240)';
        let th1 = document.createElement('th');
        th1.setAttribute('align', 'center');
        th1.textContent = '成績';
        let th2 = document.createElement('th');
        th2.setAttribute('align', 'center');
        th2.textContent = 'GPA';
        headerRow.appendChild(th1);
        headerRow.appendChild(th2);
        table.appendChild(headerRow);
			
		let GPA_name="";
        let link="";
			
        if (GPA_sort === 'NKUST') {
			GPA_name="高科4.0GPA計算方式";
			link='https://acad.nkust.edu.tw/var/file/4/1004/img/382/L-7-1re(1).pdf';
            for (let j = 0; j < 4; j++) {
                let row = document.createElement('tr');
				row.style.backgroundColor='rgb(200,200,250)';
                let td1 = document.createElement('td');
                td1.setAttribute('align', 'center');
                td1.textContent = '小於' + (50 + j * 10);
                let td2 = document.createElement('td');
                td2.setAttribute('align', 'center');
                td2.textContent = j;
                row.appendChild(td1);
                row.appendChild(td2);
                table.appendChild(row);
            }
            let lastRow = document.createElement('tr');
			lastRow.style.backgroundColor='rgb(200,200,250)';
            let td1 = document.createElement('td');
            td1.setAttribute('align', 'center');
            td1.textContent = '大於等於80';
            let td2 = document.createElement('td');
            td2.setAttribute('align', 'center');
            td2.textContent = '4';
            lastRow.appendChild(td1);
            lastRow.appendChild(td2);
            table.appendChild(lastRow);
        }
		else if (GPA_sort === 'TW0') {
			GPA_name="台灣4.0GPA計算方式";
			link='https://www.tkbgo.com.tw/zone/english/news/toNewsDetail.jsp?news_id=4872#target3-2';
            let data = [
                ['60以下', '0'],
                ['60-62', '0.7'],
                ['63-66', '1.0'],
                ['67-69', '1.3'],
                ['70-72', '1.7'],
                ['73-76', '2.0'],
                ['77-79', '2.3'],
                ['80-82', '2.7'],
                ['83-86', '3.0'],
                ['87-89', '3.3'],
                ['90-92', '3.7'],
                ['93-100', '4.0']
            ];
            data.forEach(function(rowData) {
                let row = document.createElement('tr');
				row.style.backgroundColor='rgb(200,200,250)';
                let td1 = document.createElement('td');
                td1.setAttribute('align', 'center');
                td1.textContent = rowData[0];
                let td2 = document.createElement('td');
                td2.setAttribute('align', 'center');
                td2.textContent = rowData[1];
                row.appendChild(td1);
                row.appendChild(td2);
                table.appendChild(row);
            });
        }
		else if (GPA_sort === 'TW3') {
			GPA_name="台灣4.3GPA計算方式";
			link='https://www.tkbgo.com.tw/zone/english/news/toNewsDetail.jsp?news_id=4872#target3-2';
            let data = [
                ['60以下', '0'],
                ['60-62', '1.7'],
                ['63-66', '2.0'],
                ['67-69', '2.3'],
                ['70-72', '2.7'],
                ['73-76', '3.0'],
                ['77-79', '3.3'],
                ['80-84', '3.7'],
                ['85-89', '4.0'],
                ['90-100', '4.3']
            ];
            data.forEach(function(rowData) {
                let row = document.createElement('tr');
				row.style.backgroundColor='rgb(200,200,250)';
                let td1 = document.createElement('td');
                td1.setAttribute('align', 'center');
                td1.textContent = rowData[0];
                let td2 = document.createElement('td');
                td2.setAttribute('align', 'center');
                td2.textContent = rowData[1];
                row.appendChild(td1);
                row.appendChild(td2);
                table.appendChild(row);
            });
        }
        return {tableHTML: table.outerHTML, link: link,GPA_name:GPA_name};
    }
		
    function openTableInNewWindow(GPA_sort) {//用來顯示學期成績公式的視窗
		let result = createTable(GPA_sort);
		let str2='<h3>'+result.GPA_name+'</h2>';
		let str3=result.tableHTML;
		let str4='<br><a href="'+result.link+'" target="_blank">GPA資料來源</a>';
		let str5 = '<p><input type="button" value="確認" onclick="pic_close()" />';
		document.getElementById('my_pic').innerHTML =str2 + str3 + str4 + str5;
			
		document.getElementById('my_back').style.display = "block";
		document.getElementById('my_pic').style.display = "block";
    }
	
	function openTableInNewWindowgrade() {//用來顯示GPA換算表格的視窗
		let str1 = '<p>';
		let str2='<b>學期成績計算公式：(各科成績 * 各科學分) 全相加後 / 總學分</b>';
		let str3 = '<p><input type="button" value="確認" onclick="pic_close()" />';
		document.getElementById('my_pic').innerHTML =str1 + str2 + str3;
			
		document.getElementById('my_back').style.display = "block";
		document.getElementById('my_pic').style.display = "block";
    }
	
	
	
	function openinputInNewWindow() {//用來顯示詢問要輸入幾科的視窗
		let str1 = '<p><form align="center" action="GPA_enter.php" method="POST">';
		let str2='<input placeholder="請輸入你有幾科" type="number" name="number_of_subjects" required minlength="1" maxlength="3" size="20" /><br></br>';
		let str3='<div class="container"><input type="submit" value="確認"/><br></br>';
		let str4='<div class="spacer" style="width: 10px;"></div>';
		let str5 = '<p><input type="button" value="取消" onclick="pic_close()" /></div></form>';
		document.getElementById('my_pic').innerHTML =str1 + str2 + str3 +str4 + str5;
			
		document.getElementById('my_back').style.display = "block";
		document.getElementById('my_pic').style.display = "block";
    }

	function pic_close() {//關閉彈跳視窗
		document.getElementById('my_back').style.display = "none";
		document.getElementById('my_pic').style.display = "none";
	}