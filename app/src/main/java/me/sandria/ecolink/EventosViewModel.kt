package me.sandria.ecolink

import Evento
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel

class EventosViewModel : ViewModel() {
    private val _eventoSeleccionado = MutableLiveData<Evento?>()
    val eventoSeleccionado: LiveData<Evento?> = _eventoSeleccionado

    fun seleccionarEvento(evento: Evento) {
        _eventoSeleccionado.value = evento
    }

    fun limpiarEvento() {
        _eventoSeleccionado.value = null
    }
}
