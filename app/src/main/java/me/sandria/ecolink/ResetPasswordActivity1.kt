package me.sandria.ecolink

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import com.google.firebase.auth.FirebaseAuth

// ... Otras importaciones y código ...

class ResetPasswordActivity1 : AppCompatActivity() {

    private lateinit var emailEditText: EditText
    private lateinit var auth: FirebaseAuth

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_reset_password1)

        // Inicializar Firebase Auth
        auth = FirebaseAuth.getInstance()

        // Inicialización de vistas
        emailEditText = findViewById(R.id.emailEditText)
        val sendResetLinkButton: Button = findViewById(R.id.sendResetLinkButton)

        sendResetLinkButton.setOnClickListener {
            val emailAddress = emailEditText.text.toString().trim()

            if (emailAddress.isNotEmpty()) {
                auth.sendPasswordResetEmail(emailAddress)
                    .addOnCompleteListener { task ->
                        if (task.isSuccessful) {
                            Toast.makeText(this, "Correo de recuperación enviado.", Toast.LENGTH_LONG).show()
                        } else {
                            Toast.makeText(this, "Error al enviar correo de recuperación.", Toast.LENGTH_LONG).show()
                        }
                    }
            } else {
                Toast.makeText(this, "Por favor, ingresa tu correo electrónico primero.", Toast.LENGTH_LONG).show()
            }
        }
    }

    // Resto de tu código...
}
