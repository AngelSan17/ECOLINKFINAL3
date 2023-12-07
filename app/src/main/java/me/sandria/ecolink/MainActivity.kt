package me.sandria.ecolink // Esto define el paquete al que pertenece tu archivo

import android.annotation.SuppressLint
import android.os.Bundle
import androidx.appcompat.app.AppCompatActivity
import android.widget.Button
import android.content.Intent

class MainActivity : AppCompatActivity() {
    @SuppressLint("MissingInflatedId")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        // Inicializar las vistas
        val loginButton: Button = findViewById(R.id.loginBotton)
        val registerButton: Button = findViewById(R.id.registerBotton)

        // Establecer los eventos de clic
        loginButton.setOnClickListener {
            // Navegar a LoginActivity
            val intent = Intent(this, LoginActivity::class.java)
            startActivity(intent)
        }

        registerButton.setOnClickListener {
            val intent = Intent(this, RegisterActivity::class.java)
            startActivity(intent)
        }

    }
    }

