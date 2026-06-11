<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            [
                'titulo'         => 'Recetas Personalizadas para Jóvenes Adultos Saludables',
                'slug'           => 'recetas-personalizadas-jovenes-adultos',
                'autor'          => 'Selina Albino',
                'imagen'         => 'cat-snacks.jpg',
                'tiempo_lectura' => 3,
                'vistas'         => 5,
                'extracto'       => 'La alimentación saludable es un aspecto fundamental para el bienestar de los jóvenes adultos. Descubre recetas fáciles, nutritivas y adaptables.',
                'contenido'      => '
                    <p>La alimentación saludable es un aspecto fundamental para el bienestar de los jóvenes adultos. Con un estilo de vida ajetreado y la presión de equilibrar estudios, trabajo y vida social, es fácil caer en la trampa de las comidas rápidas y poco nutritivas. Sin embargo, preparar recetas personalizadas no solo es posible, sino que también puede ser divertido y satisfactorio.</p>

                    <h2>Adaptaciones para Diferentes Dietas</h2>
                    <p>Es importante que cada receta se pueda adaptar a diferentes necesidades dietéticas. Aquí hay algunas sugerencias:</p>
                    <ul>
                        <li><strong>Sin gluten:</strong> Utiliza tortillas de maíz en lugar de tortillas de trigo y asegúrate de que la quinoa esté etiquetada como sin gluten.</li>
                        <li><strong>Vegana:</strong> Sustituye el yogur griego por un yogur de coco o almendra y utiliza tofu en lugar de pescado en los tacos.</li>
                        <li><strong>Baja en carbohidratos:</strong> Reduce la cantidad de quinoa en la ensalada y utiliza hojas de lechuga en lugar de tortillas para los tacos.</li>
                    </ul>

                    <h2>Consejos para Mantener una Alimentación Saludable</h2>
                    <ol>
                        <li><strong>Planifica tus comidas:</strong> Dedica un tiempo cada semana para planificar tus comidas. Esto te ayudará a evitar decisiones impulsivas.</li>
                        <li><strong>Haz una lista de compras:</strong> Antes de ir al supermercado, haz una lista de los ingredientes que necesitas.</li>
                        <li><strong>Cocina en lotes:</strong> Prepara grandes cantidades de tus recetas favoritas y congélalas en porciones individuales.</li>
                    </ol>

                    <h2>La Importancia de la Hidratación</h2>
                    <p>No olvides que la hidratación es clave para una buena salud. Beber suficiente agua puede mejorar tu energía y concentración a lo largo del día.</p>

                    <h2>Conclusión</h2>
                    <p>Adoptar un estilo de vida saludable no tiene que ser complicado. Con recetas personalizadas y un poco de planificación, puedes disfrutar de comidas deliciosas y nutritivas que se adapten a tus gustos y necesidades. ¡Empieza hoy mismo a experimentar en la cocina y descubre lo fácil que es comer bien!</p>
                    <p>Con estas recetas y consejos, estarás en el camino correcto hacia una alimentación más saludable. ¡Anímate a probarlas y comparte tus experiencias con amigos y familiares!</p>
                ',
            ],
            [
                'titulo'         => 'Nutrición Inteligente: Mejora Tu Salud Con Pequeños Cambios',
                'slug'           => 'nutricion-inteligente-mejora-tu-salud',
                'autor'          => 'Selina Albino',
                'imagen'         => 'cat-suplementos.jpg',
                'tiempo_lectura' => 3,
                'vistas'         => 3,
                'extracto'       => 'La nutrición es un pilar fundamental para mantener una buena salud. Aprende cómo pequeños cambios diarios generan grandes resultados.',
                'contenido'      => '
                    <p>La nutrición es un pilar fundamental para mantener una buena salud física y mental. No se trata de dietas extremas, sino de construir hábitos sostenibles que potencien tu energía y bienestar día a día.</p>

                    <h2>Empieza por lo básico</h2>
                    <p>Antes de pensar en suplementos, asegúrate de cubrir lo esencial: variedad de vegetales, proteínas de calidad, grasas saludables y suficiente agua.</p>
                    <ul>
                        <li><strong>Colorea tu plato:</strong> mientras más colores naturales, más variedad de nutrientes.</li>
                        <li><strong>Reduce los ultraprocesados:</strong> elige alimentos lo más cercanos a su estado natural.</li>
                        <li><strong>Escucha a tu cuerpo:</strong> comer con atención plena mejora la digestión y la saciedad.</li>
                    </ul>

                    <h2>El rol de los suplementos</h2>
                    <p>Los suplementos son un complemento, no un reemplazo. Bien elegidos, ayudan a cubrir necesidades específicas como energía, defensas o recuperación muscular.</p>

                    <h2>Conclusión</h2>
                    <p>La nutrición inteligente es un camino, no un destino. Con pequeños cambios consistentes vas a notar mejoras reales en tu energía, foco y bienestar general.</p>
                ',
            ],
            [
                'titulo'         => 'Vitamind: Bienestar y Alimentación Consciente',
                'slug'           => 'vitamind-bienestar-alimentacion-consciente',
                'autor'          => 'Selina Albino',
                'imagen'         => 'cat-recetas.jpg',
                'tiempo_lectura' => 5,
                'vistas'         => 0,
                'extracto'       => 'La búsqueda del bienestar y la alimentación consciente se ha vuelto esencial. Conoce la filosofía detrás de VitaMind.',
                'contenido'      => '
                    <p>La búsqueda del bienestar y la alimentación consciente se ha vuelto esencial en el mundo acelerado de hoy. En VitaMind creemos que comer bien es un acto de autocuidado que impacta todo: tu energía, tu foco y tu estado de ánimo.</p>

                    <h2>¿Qué es la alimentación consciente?</h2>
                    <p>Es prestar atención plena a lo que comes, cómo lo comes y cómo te hace sentir. Significa elegir con intención en lugar de comer en piloto automático.</p>

                    <h2>Nuestra filosofía</h2>
                    <ul>
                        <li><strong>Productos seleccionados:</strong> snacks, bebidas y suplementos pensados para tu bienestar.</li>
                        <li><strong>Tecnología a tu favor:</strong> nuestro asistente con IA te recomienda lo ideal según tu objetivo.</li>
                        <li><strong>Educación:</strong> guías y recetas para que aprendas a comer mejor cada día.</li>
                    </ul>

                    <h2>Conclusión</h2>
                    <p>El bienestar no es un destino, es un estilo de vida. Con alimentación consciente y las herramientas correctas, alcanzar tu mejor versión está más cerca de lo que crees.</p>
                ',
            ],
        ];

        foreach ($posts as $datos) {
            Post::updateOrCreate(['slug' => $datos['slug']], $datos);
        }
    }
}
