import SUV from "../components/assets/images/cars/suv.png";
import Truck from "../components/assets/images/cars/truck.png";
import Sedan from "../components/assets/images/cars/sedan.png";
import Minivan from "../components/assets/images/cars/minivan.png";
import Convertible from "../components/assets/images/cars/convertible.png";
import Hatchback from "../components/assets/images/cars/hatchback.png";
import Coupe from "../components/assets/images/cars/coupe.png";
import WindshieldIcon from "../components/assets/images/icons/windshield.png";
import SideGlassIcon from "../components/assets/images/icons/side_door.png";
import SunroofIcon from "../components/assets/images/icons/sunroof.png";
import RearWindshieldIcon from "../components/assets/images/icons/rear.png";

export const CarTypes = [
  { name: "Sedan", qName: "car", image: Sedan },
  { name: "Truck", qName: "truck", image: Truck },
  { name: "SUV", qName: "vehicle", image: SUV },
  { name: "Minivan", qName: "vehicle", image: Minivan },
  { name: "Convertible", qName: "vehicle", image: Convertible },
  { name: "Hatchback", qName: "vehicle", image: Hatchback },
  { name: "Coupe", qName: "car", image: Coupe },
];

export const AdditionalOptions = [
  { label: "Rain Sensors", name: "rain_sensors" },
  { label: "Rain & Lane Sensors", name: "rain_lane_sensors" },
  { label: "Heads-Up Display", name: "heads_up_display" },
  { label: "Not Sure", name: "not_sure" },
];

export const GlassTypes = [
  {
    title: "Windshield",
    description: "Premium glass with optimal safety standards.",
    time: "90 min",
    icon: WindshieldIcon,
  },
  {
    title: "Side Glass",
    description: "Enhanced durability and thermal protection.",
    time: "60 min",
    icon: SideGlassIcon,
  },
  {
    title: "Back Glass",
    description: "Technology integrated for safety and convenience.",
    time: "120 min",
    icon: RearWindshieldIcon,
  },
  {
    title: "Sunroof",
    description: "Enhanced protection with advanced design.",
    time: "120 min",
    icon: SunroofIcon,
  },
];
