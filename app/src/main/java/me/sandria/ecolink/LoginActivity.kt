package me.sandria.ecolink

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import android.content.Intent
import com.google.firebase.auth.FirebaseAuth
import androidx.appcompat.app.AlertDialog



class LoginActivity : AppCompatActivity() {

    private lateinit var emailEditText: EditText
    private lateinit var passwordEditText: EditText
    private lateinit var forgotPasswordTextView: TextView
    private lateinit var auth: FirebaseAuth

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        auth = FirebaseAuth.getInstance()

        emailEditText = findViewById(R.id.emailEditText)
        passwordEditText = findViewById(R.id.passwordEditText)
        forgotPasswordTextView = findViewById(R.id.forgotPasswordText) // Asegúrate de que este ID sea el correcto

        val loginButton: Button = findViewById(R.id.loginBotton) // Asegúrate de que este ID sea el correcto
        loginButton.setOnClickListener {
            val email = emailEditText.text.toString().trim()
            val password = passwordEditText.text.toString().trim()
            performLogin(email, password)
        }

        // Establecer el OnClickListener para el TextView de olvidar contraseña
        forgotPasswordTextView.setOnClickListener {
            // Inicia la ResetPasswordActivity1 cuando se hace clic en el TextView
            val resetPasswordIntent = Intent(this, ResetPasswordActivity1::class.java)
            startActivity(resetPasswordIntent)
        }
    }

    private fun performLogin(email: String, password: String) {
        if (email.isNotEmpty() && password.isNotEmpty()) {
            auth.signInWithEmailAndPassword(email, password)
                .addOnCompleteListener(this) { task ->
                    if (task.isSuccessful) {
                        // Inicio de sesión exitoso, navega a la siguiente actividad
                        val intent = Intent(this, CommunityActivity::class.java)
                        startActivity(intent)
                        finish()
                    } else {
                        // Si la autenticación falla, muestra un mensaje al usuario
                        Toast.makeText(this, "Authentication failed: ${task.exception?.localizedMessage}", Toast.LENGTH_SHORT).show()
                    }
                }
        } else {
            // Mostrar mensaje de error si los campos están vacíos
            Toast.makeText(this, "Please enter both email and password", Toast.LENGTH_SHORT).show()
        }
    }
}
