<?php

				function token($length){
					//générateur du token aleatoire pour le mot de passe oublier
					$characters='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz9876543210'.time();
					$charactersLength = strlen($characters);
					$randomString = '';

					for ($i = $length; $i > 0; $i--){
							$randomString .= $characters[rand(0, $charactersLength - 1)];
					}
					return $randomString;
				}

				function code($length){
					//générateur du token aleatoire pour le mot de passe oublier
					$characters='0123456789'.time();
					$charactersLength = strlen($characters);
					$randomString = '';

					for ($i = $length; $i > 0; $i--){
						$randomString .= $characters[rand(0, $charactersLength - 1)];
					}
					return $randomString;
				} 
?>