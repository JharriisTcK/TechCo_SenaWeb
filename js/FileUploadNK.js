/*jshint esversion: 6 */
/*
//todos los llamados JX deben responder con un objeto JSON
@return {
	RespuestaBool: boolean //Si el llamado fue verdadero o falso
	RespuestaError: TEXT //El texto que identique el error
}

//Obtener Archivos
FileUploadNK = ArchivosGet
@return {
	Archivos[]: {
		ArchivoID:  ,
		Src:  ,
        Nombre: ,
		Descripcion:
		ArchivoSize:
	}
}

//Subir Imagen
FileUploadNK = ArchivoSubir
Caption = TEXT
Imagen = FILE
@return {
	id_image:
}

//Eliminar Imagen
FileUploadNK = ArchivoEliminar
KeyJX = TEXT
ArchivoID = TEXT 					//imagen a eliminar

*/

class FileUploadNK {
	constructor(nodeIn, KeyJX, urlAction, dirRaiz, objOptionsIn) {
		this.KeyJX = KeyJX;
		this.urlAction = urlAction || "NO DEFINIDO";
		this.dirRaiz = dirRaiz;
		this.nodeObj = nodeIn || document.createElement("div");
		//------------------
		objOptionsIn = objOptionsIn || {};
		this.objOptions = {
			valuesIn: objOptionsIn.valuesIn || [],
			Titulo: objOptionsIn.Titulo,
			filesReadedInput: [],
			valuesSend: objOptionsIn.valuesSend || [],
			multiple: objOptionsIn.multiple || false,
			ArchivosAceptar: objOptionsIn.ArchivosAceptar || ["*.*"],
			LoadingImg: objOptionsIn.LoadingImg
		};
		//------------------
		this.nnInputUpload = ""; //El input encargado de seleccionar los archivos
		this.ArchivosValue = []; //Archivos Cargados del Servidor
		//------------------
		this.nodeHeader = document.createElement("div");
		this.nodeObj.appendChild(this.nodeHeader);
		this.nodeControls = document.createElement("div");
		this.nodeObj.appendChild(this.nodeControls);
		this.nodeListaUpload = document.createElement("ul");
		this.nodeObj.appendChild(this.nodeListaUpload);
		this.nodeLista = document.createElement("div");
		this.nodeObj.appendChild(this.nodeLista);
		this.nodeEdit = document.createElement("div");
		this.nodeEdit.tabIndex = "0";
		this.nodeObj.appendChild(this.nodeEdit);
		//------------------
		this.ConfigHeaderForm();
		this.jxArchivosGet();

	}

	ConfigHeaderForm() {
		var _this = this;
		var nnDiv = this.nodeHeader;
		nnDiv.innerHTML = "";
		var nnTitulo = document.createElement("h2");
		nnTitulo.innerHTML = this.objOptions.Titulo;
		nnDiv.appendChild(nnTitulo);
		var nnLabel = document.createElement("label");
		nnDiv.appendChild(nnLabel);
		var nnTexto = document.createElement("b");
		nnTexto.innerHTML = "📎​ Seleccionar Archivos 📁";
		nnLabel.appendChild(nnTexto);
		var nnInputFile = document.createElement("input");
		nnInputFile.type = "file";
		nnInputFile.name = "FileInputUploadNK";
		this.nnInputUpload = nnInputFile;
		if (this.objOptions.multiple === true) {
			nnInputFile.setAttribute("multiple", true);
		}
		for(var i=0; i<this.objOptions.ArchivosAceptar.length; i++) {
			nnInputFile.accept=this.objOptions.ArchivosAceptar.toString();
		}
		nnLabel.appendChild(nnInputFile);
		//-------
		var nnDivControls = this.nodeControls;
		nnDivControls.innerHTML = "";
		var nnDivButtonReloadFiles = document.createElement("button");
		nnDivButtonReloadFiles.innerHTML = "🔁​ Recargar Archivos";
		nnDivControls.appendChild(nnDivButtonReloadFiles);
		//---------------------------------------
		nnInputFile.addEventListener("change", function (e) {
			_this.LeerArhivosInput(e, nnInputFile);
		}, false);
		nnDivButtonReloadFiles.onclick = function () {
			_this.jxArchivosGet();
		};
	}

	LeerArhivosInput(ev, nnInputFile) {
		var files = ev.target.files;
		this.nodeListaUpload.innerHTML = "";
		var ArchivosTitulo=document.createElement("h3");
		ArchivosTitulo.innerHTML="Archivos para Subir";
		this.nodeListaUpload.appendChild(ArchivosTitulo);
		var ArchivosListaUp=document.createElement("ul");
		this.nodeListaUpload.appendChild(ArchivosListaUp);
		for (var i = 0; i < files.length; i++) {
			this.LeerArchivoItem(files[i], ArchivosListaUp);
		} //Fin Bucle Imagenes Append
		nnInputFile.value = [];
		this.nodeListaUpload.focus();
	}

	LeerArchivoItem(ArchivoUpItem, NodeLista) {
		var _this = this;
		var nnLi = document.createElement("li");
		NodeLista.appendChild(nnLi);
		var nodeInfo = document.createElement("p");
		nodeInfo.innerHTML = ArchivoUpItem.name + " [" + (neoKiri.formatSizeUnits(ArchivoUpItem.size)) + "]";
		nnLi.appendChild(nodeInfo);
		var nodeForm = document.createElement("form");
		nodeForm.method = "POST";
		nodeForm.enctype = "multipart/form-data";
		nodeForm.action = this.urlAction;
		nnLi.appendChild(nodeForm);
		//---------------------
		var nodeNombreDiv = document.createElement("div");
		nodeForm.appendChild(nodeNombreDiv);
		var nodeNombreLabel = document.createElement("label");
		nodeForm.appendChild(nodeNombreLabel);
		var nodeNombreB = document.createElement("b");
		nodeNombreB.innerHTML = "Nombre";
		nodeNombreLabel.appendChild(nodeNombreB);
		var nodeNombre = document.createElement("input");
		nodeNombre.required = "required";
		nodeNombre.placeholder = "Ingresa un Nombre*";
		nodeNombreLabel.appendChild(nodeNombre);
		//---------------------
		var nodeDescripcionDiv = document.createElement("div");
		nodeForm.appendChild(nodeDescripcionDiv);
		var nodeDescripcionLabel = document.createElement("label");
		nodeForm.appendChild(nodeDescripcionLabel);
		var nodeDescripcionB = document.createElement("b");
		nodeDescripcionB.innerHTML = "Descripcion";
		nodeDescripcionLabel.appendChild(nodeDescripcionB);
		var nodeDescription = document.createElement("textarea");
		nodeDescripcionLabel.appendChild(nodeDescription);
		//---------------------
		var nodeControls = document.createElement("div");
		nodeForm.appendChild(nodeControls);
		var nodeUpload = document.createElement("input");
		nodeUpload.type = "submit";
		nodeUpload.value = "⏫​ Subir Archivo";
		nodeForm.appendChild(nodeUpload);
		var nodeCancel = document.createElement("input");
		nodeCancel.type = "reset";
		nodeCancel.value = "❌​ Cancelar";
		nodeCancel.className = "BotonAmarillo";
		nodeForm.appendChild(nodeCancel);
		//---------------------
		var fileObj = {
			file: ArchivoUpItem,
			fileBox: nnLi,
			fileNombre: "",
			fileDescription: ""
		};
		//------------------------
		nodeInfo.onclick = function () {
			console.log(fileObj);
		};
		nodeDescription.onchange = function () {
			fileObj.fileDescription = nodeDescription.value;
		};
		nodeNombre.onchange = function () {
			fileObj.fileNombre = nodeNombre.value;
		};
		nodeForm.onsubmit = function (e) {
			e.preventDefault();
			_this.jxArchivoSubir(fileObj);
		};
		nodeForm.onreset = function () {
			NodeLista.removeChild(nnLi);
		};
	}



	ConfigValues() {
		this.nodeLista.innerHTML = "";
		if(!this.ArchivosValue.length) {
			return false;
		}
		var nodeTituloValues = document.createElement("h3");
		nodeTituloValues.innerHTML = "Archivos";
		this.nodeLista.appendChild(nodeTituloValues);
		var nodeLista = document.createElement("ul");
		this.nodeLista.appendChild(nodeLista);
		for (var i = 0; i < this.ArchivosValue.length; i++) {
			this.ConfigValueItem(this.ArchivosValue[i], nodeLista);
		}
	}

	ConfigValueItem(ArchivoItem, nnLista) {
		/*
		ArchivoID: 3
		Descripcion: "svg"
		SrcH: "text Url"
		*/
		var _this = this;
		var nnLi = document.createElement("li");
		nnLista.appendChild(nnLi);
		var nnDivInfo = document.createElement("div");
		nnLi.appendChild(nnDivInfo);
		var nnA = document.createElement("a");
		nnA.href = this.dirRaiz + ArchivoItem.Src;
		nnA.target = "_BLANK";
		nnDivInfo.appendChild(nnA);
		var nnB = document.createElement("b");
		nnB.innerHTML = ArchivoItem.Nombre + " [" + neoKiri.formatSizeUnits(ArchivoItem.ArchivoSize) + "]";
		nnA.appendChild(nnB);
		var nnPDescripcion = document.createElement("p");
		nnPDescripcion.innerHTML = ArchivoItem.Descripcion;
		nnLi.appendChild(nnPDescripcion);
		var nnControls = document.createElement("div");
		nnLi.appendChild(nnControls);
		var nnButtonEditar = document.createElement("button");
		nnButtonEditar.innerHTML = "✍️​ Editar";
		nnControls.appendChild(nnButtonEditar);
		var nnButtonDelete = document.createElement("button");
		nnButtonDelete.innerHTML = "🗑️​ Eliminar";
		nnButtonDelete.className = "BotonRojo";
		nnControls.appendChild(nnButtonDelete);
		//-----------
		ArchivoItem.nodeValueBox = nnLi;
		//-------
		nnButtonDelete.onclick = function () {
			_this.jxArchivoEliminar(ArchivoItem, nnButtonDelete);
		};
	}

	jxArchivosGet() {
		this.nodeListaUpload.innerHTML="";
		this.nodeLista.innerHTML="";
		this.ArchivosValue=[];
		if(this.objOptions.LoadingImg) {
			this.nodeLista.innerHTML="<img src='"+this.objOptions.LoadingImg+"' />";
		}
		var fd = new FormData();
		fd.append("FileUploadNK", "ArchivosGet");
		fd.append("KeyJX", this.KeyJX);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {
			method: "POST",
			body: fd
		})
		.then(resp => resp.json())
		.then(data => {
			console.group("FileUploadNK::jxArchivosGet() - " + this.objOptions.Titulo);
			console.log(data);
			console.groupEnd("");
			this.nodeLista.innerHTML="";
				if (data.RespuestaBool) {
					this.ArchivosValue = data.Archivos;
					this.ConfigValues();
				}
			});
	}

	jxArchivoSubir(ArchivoUpItem) {
		var _this = this;
		var fd = new FormData();
		fd.append("FileUploadNK", "ArchivoSubir");
		fd.append("Archivo", ArchivoUpItem.file);
		fd.append("Nombre", ArchivoUpItem.fileNombre || "");
		fd.append("Descripcion", ArchivoUpItem.fileDescription || "");
		fd.append("KeyJX", this.KeyJX);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		var jx = new XMLHttpRequest();
		jx.upload.addEventListener("progress", function (e) {
			var percentage = Math.round((e.loaded * 100) / e.total);
			var loaded = neoKiri.formatSizeUnits(e.loaded);
			var total = neoKiri.formatSizeUnits(e.total);
			//neoKiri.boxProgress(fileObjIn.fileBox, fileObjIn.file.name, loaded, total, jx);
			ArchivoUpItem.fileBox.innerHTML = loaded + "/" + total + " [" + percentage + "%]";
		}, true);
		jx.onreadystatechange = function () {
			switch (jx.status) {
				case 200:
					switch (jx.readyState) {
						case 4:
							console.group("FileUploadNK::jxArchivoSubir() - UploadComplete");
							console.log(jx.responseText);
							var responseObj = JSON.parse(jx.responseText);
							console.log(responseObj);
							console.groupEnd();
							if (responseObj.RespuestaBool) {
								ArchivoUpItem.fileBox.innerHTML = "Subida Completa: " + ArchivoUpItem.file.name;
								_this.jxArchivosGet();
							} else {
								console.error(responseObj);
							}
							break;
					}
					break;
			}
		};
		jx.open("POST", this.urlAction, true);
		jx.send(fd);
	}

	jxArchivoEliminar(ArchivoItem, Boton) {
		Boton.disabled = true;
		var BotonValue = Boton.innerHTML;
		Boton.innerHTML = "🗑️​ Eliminando Archivo...";
		var fd = new FormData();
		fd.append("FileUploadNK", "ArchivoEliminar");
		fd.append("KeyJX", this.KeyJX);
		fd.append("ArchivoID", ArchivoItem.ArchivoID);
		for (var i = 0; i < this.objOptions.valuesSend.length; i++) {
			fd.append(this.objOptions.valuesSend[i][0], this.objOptions.valuesSend[i][1]);
		}
		fetch(this.urlAction, {
				method: "POST",
				body: fd
			})
			.then(resp => resp.json())
			.then(data => {
				console.group("FileUlpoadNK::jxArchivoEliminar()");
				console.log(data);
				if (data.RespuestaBool) {
					Boton.innerHTML = BotonValue;
					Boton.disabled = false;
					this.jxArchivosGet();
				}
				console.groupEnd();
			});
	}
}