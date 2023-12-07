package me.sandria.ecolink

import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import android.content.Intent
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.google.firebase.auth.FirebaseAuth
import org.json.JSONObject

class RegisterActivity : AppCompatActivity() {

    private lateinit var nameEditText: EditText
    private lateinit var surnameEditText: EditText
    private lateinit var emailEditText: EditText
    private lateinit var passwordEditText: EditText
    private lateinit var registerButton: Button
    private lateinit var auth: FirebaseAuth

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        // Inicializar Firebase Auth
        auth = FirebaseAuth.getInstance()

        // Inicialización de variables con los IDs de los EditTexts y Button del layout
        nameEditText = findViewById(R.id.etName)
        surnameEditText = findViewById(R.id.etSurname)
        emailEditText = findViewById(R.id.etEmail)
        passwordEditText = findViewById(R.id.etPassword)
        registerButton = findViewById(R.id.registerBotton)

        registerButton.setOnClickListener { onRegisterClicked() }
    }

    private fun onRegisterClicked() {
        val name = nameEditText.text.toString().trim()
        val surname = surnameEditText.text.toString().trim()
        val email = emailEditText.text.toString().trim()
        val password = passwordEditText.text.toString().trim()

        if (name.isNotEmpty() && surname.isNotEmpty() && email.isNotEmpty() && password.isNotEmpty()) {
            // Registrar al usuario en Firebase
            auth.createUserWithEmailAndPassword(email, password)
                .addOnCompleteListener(this) { task ->
                    if (task.isSuccessful) {
                        val id_user = auth.currentUser?.uid
                        // El registro fue exitoso
                        println(id_user)
                        dbRegister(id_user,name,email)

                    } else {
                        // Si falla el registro, muestra un mensaje al usuario
                        Toast.makeText(this, "Registro fallido: ${task.exception?.localizedMessage}", Toast.LENGTH_SHORT).show()
                    }
                }
        } else {
            Toast.makeText(this, "Por favor, llena todos los campos", Toast.LENGTH_SHORT).show()
        }
    }

    private fun dbRegister(_id :String?,_nombre : String,_numero : String){

        val requestQueue = Volley.newRequestQueue(this)
        val url : String = "http://192.168.1.68/ecolink/usuarios"
        val jsonRequest = JSONObject()
        jsonRequest.put("id_usuario", _id)
        jsonRequest.put("nombre_usuario", _nombre)
        jsonRequest.put("email", _numero)
        var method = Request.Method.POST

        val request : JsonObjectRequest = JsonObjectRequest(
            method,
            url,
            jsonRequest,
            Response.Listener { response ->
                //val message : String = ProcesarRespuesta(response)
                Toast.makeText(this, "Registro exitoso", Toast.LENGTH_SHORT).show()
                // Aquí puedes también iniciar LoginActivity si lo deseas
                val intent = Intent(this, LoginActivity::class.java)
                startActivity(intent)
                finish()
            }, Response.ErrorListener { error ->
                println(error.cause)
                Toast.makeText(this,"Error", Toast.LENGTH_SHORT).show()
            }
        )
        requestQueue.add(request)
    }

    private fun ProcesarRespuesta(response : JSONObject?): String{
        if (response != null){
            val name : String = response.getString("response")
            println(response.getString("content"))
            return name
        }
        return ""
    }
}
