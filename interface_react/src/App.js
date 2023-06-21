// Librairies
import { BrowserRouter, Routes } from "react-router-dom";
import { Route } from "react-router";
import { useState } from "react";

// Composants
import Header from "./composants/Header";
import Exercices from "./pages/Exercices/Exercices";
import Login from "./pages/Login";
import Contexte from './Contexte';

import "./styles/composants.scss";
import "./styles/index.scss";
import "./styles/pages.scss";

function App() {

  const apiAccess = async ({
    url,
    method = "get",
    body = undefined,
    // needAuth = true
  }) => {
    // -- CONSTANTES --
    const errorMessages = {
      400: "Requete mal formée",
      401: "Authentification necessaire",
      403: "Vous ne pouvez pas accéder à ces données",
      404: "La ressource n'existe pas",
      422: "Mauvais format de reponse",
      503: "Service indisponible (surcharge ou maintenance)",
      default: "Erreur de serveur"
    }

    // // -- PRE-TRAITEMENTS --
    // // Verificaton de l authentification
    // const token = getToken();
    // console.log(token);
    // if (needAuth && !token) {
    //   // Aucun utilisateur connecte
    //   setEstConnecte(false);
    //   return {
    //     success: false,
    //     statusCode: 401,
    //     datas: undefined,
    //     erreur: errorMessages[401]
    //   };
    // }

    // -- TRAITEMENT --
    const res = await fetch(url, {
      method: method,
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        // "Authorization": needAuth && `Bearer ${token}`,
      },
      body: body && JSON.stringify(body),
    })
      .catch(err => {
        return {
          success: false,
          statusCode: 500,
          erreur: errorMessages.default
        }
      })

    // -- RETOUR --
    let retour;
    if (res.ok) {
      // La requete a reussi
      retour = {
        success: true,
        statusCode: res.status,
        datas: res.status === 204 ? {} : await res.json(),      // On ne fait pas json() s il n y a pas de contenu
      }
    }
    else {
      // La requete a echoue
      // if (needAuth && res.status === 401) {
      //   // Le tokken n est plus valide mais existe encore en local
      //   deconnexionFront();
      // }

      retour = {
        success: false,
        statusCode: res.status,
        erreur: errorMessages[res.status] || errorMessages.default,
        debugErreur: await res.json()
      }
    }

    console.log({ url, body, retour });

    return retour;
  }

  return (
    <div className="App">
      <Contexte.Provider value={{ apiAccess }}>
        <BrowserRouter>
          <Header />
          <Routes>
            <Route path="/" element={<Exercices />} />
            <Route path="/login" element={<Login />} />
          </Routes>
        </BrowserRouter>
      </Contexte.Provider>
    </div>
  );
}

export default App;
